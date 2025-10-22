<?php

namespace LiveHelperChatExtension\chatgpt\providers;

class ChatGPTLiveHelperChatValidator {

    public static $requestLog = [];

    public static function retrieveMessage($question, $department_id = 'default')
    {
        $sjOptions = \erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
        $data = (array)$sjOptions->data;

        $apiKey = $data['project_api_key'] ?? '';

        if (empty($apiKey)) {
            throw new \Exception('OpenAI API key is not configured!');
        }

        $vectorStorageData = json_decode($data['vstorage_id'],true);

        if (is_array($vectorStorageData)) {
            if (isset($vectorStorageData[$department_id])) {
                $vectorStorageId = $vectorStorageData[$department_id];
            } else {
                $vectorStorageId = $vectorStorageData['default'];
            }
        } else {
            $vectorStorageId = $data['vstorage_id'];
        }

        $model = !empty($data['model']) ? $data['model'] : 'gpt-4o-mini';

        $messages = [];

        if (is_array($question)) {
            $messages = $question;
        } else {
            $messages[] = ['role' => 'user', 'content' => $question];
        }

        if (!empty($data['system_instructions'])) {
            array_unshift($messages, ['role' => 'system', 'content' => $data['system_instructions']]);
        }

        $payload = json_encode([
            'model' => $model,
            "stream" => false,
            'input' => $messages,
            'temperature' => 0.7,
            'tools' => [
                [
                    "type" => "file_search",
                    "vector_store_ids" => [$vectorStorageId]
                ]
            ]
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/responses');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $apiKey
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);

        curl_close($ch);

        if ($error) {
            self::$requestLog[] = ['error' => $error];
            throw new \Exception('CURL Error: ' . $error);
        }

        if ($httpCode != 200) {
            self::$requestLog[] = ['response' => $response, 'http_code' => $httpCode];
            throw new \Exception('API Error: HTTP code ' . $httpCode);
        }

        $responseData = json_decode($response, true);

        if (!isset($responseData['output'][0])) {
            self::$requestLog[] = ['response' => $responseData];
            throw new \Exception('Invalid response format from OpenAI API');
        }

        foreach ($responseData['output'] as $output) {
            if ($output['type'] == 'message' && isset($output['content'][0]['text'])) {
                return $output['content'][0]['text'];
            }
        }

        return '';
    }

    public static function getAnswer($question, $chat_id = 0)
    {
        $sjOptions = \erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
        $data = (array)$sjOptions->data;

        try {
            $response = ['found' => false, 'msg' => '', 'aid' => null];
            $response['found'] = true;

            $department_id = 0;

            if ($chat_id > 0) {
                $chat = \erLhcoreClassModelChat::fetch($chat_id);
                $department_id = is_object($chat) ? $chat->dep_id : 0;
            }

            $response['msg'] = self::retrieveMessage($question, $department_id);
        } catch (\Exception $e) {
            $response['found'] = false;
            $response['error'] = $e->getMessage();
        }

        $lastInsertId = 0;

        if (isset($data['log_request']) && $data['log_request'] == 1) {

            $db = \ezcDbInstance::get();
            $db->beginTransaction();

            \erLhcoreClassLog::write(json_encode([
                'payload' => $question,
                'content' => $response,
                'request' => self::$requestLog,
            ],JSON_PRETTY_PRINT),
                \ezcLog::SUCCESS_AUDIT,
                array(
                    'source' => 'lhc',
                    'category' => 'chatgpt_ai',
                    'line' => __LINE__,
                    'file' => __FILE__,
                    'object_id' => $chat_id
                )
            );

            $lastInsertId = $db->lastInsertId();

            $db->commit();
        }

        $response['aid'] = $lastInsertId;

        return $response;
    }

    public static function suggestByIds($ids = array(), $chatMode = false)
    {
        if ($chatMode == false) {
            $msgs = \erLhcoreClassModelmsg::getList(array('filterin' => array('id' => $ids)));
        } else {
            $msgs = \erLhcoreClassModelmsg::getList(array('limit' => 5, 'sort' => 'id DESC', 'filter' => array('user_id' => 0), 'filterin' => array('chat_id' => $ids)));
        }

        $suggestions = array();

        // Select messages chat's id
        $chatsId = array();
        foreach ($msgs as $msg) {
            $chatsId[] = $msg->chat_id;
        }

        if (empty($chatsId)) {
            return array();
        }

        $chatsId = array_unique($chatsId);

        $sjOptions = \erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
        $data = (array)$sjOptions->data;

        foreach ($chatsId as $chatId) {

            $messagesProcess[$chatId] = [];

            $filterMessages = array(
                'limit' => (isset($data['limit_last']) && is_numeric($data['limit_last']) && $data['limit_last'] > 0 ? (int)$data['limit_last'] : 5),
                'sort' => 'id DESC',
                'filternotin' => ['user_id' => [-1,-2]],
                'filterin' => array('chat_id' => $chatId));

            $filterMessages['filter']['user_id'] = 0;

            $messagesChat = array_reverse(\erLhcoreClassModelmsg::getList($filterMessages));

            $presentRole = '';
            $previousRole = '';
            $messagesContent = '';
            $visitorMessageFirst = false;

            foreach ($messagesChat as $msgChat) {

                $msgSearch = trim(preg_replace('#([\x{2B50}-\x{2B55}]|[\x{23F0}-\x{23F3}]|[\x{231A}-\x{231B}]|[\x{1F600}-\x{1F64F}]|[\x{1F910}-\x{1F9FF}]|[\x{1F300}-\x{1F5FF}]|[\x{1F680}-\x{1F6FF}]|[\x{2600}-\x{26FF}]|[\x{2700}-\x{27BF}])#u','', $msgChat->msg));

                if ($msgSearch == '') {
                    continue;
                }

                if ($msgChat->user_id == 0) {
                    $presentRole = 'user';
                } else {
                    $presentRole = 'assistant';
                }

                if ($presentRole == 'user' && ($previousRole == 'user' || $previousRole == '')) {
                    $previousRole = 'user';
                }

                if ($previousRole == 'user') {
                    $visitorMessageFirst = true;
                }

                if ($presentRole == 'assistant' && ($previousRole == 'assistant' || $previousRole == '')) {
                    $previousRole = 'assistant';
                }

                if ($presentRole != $previousRole) {

                    if ($previousRole == 'assistant') {
                        $chat = \erLhcoreClassModelChat::fetch($chatId);
                        $messagesContent = str_replace([' '.$chat->plain_user_name,' '.$chat->user->name],' <employee>',$messagesContent);

                        if (is_object($chat->online_user) && is_object($chat->online_user->operator_user)) {
                            $messagesContent = str_replace([' '.$chat->online_user->operator_user->name_support,' '.$chat->online_user->operator_user->name],' <employee>',$messagesContent);
                        }
                    }

                    if ($visitorMessageFirst === true || $previousRole == 'user') {
                        $messagesProcess[$chatId][] = ['role' => $previousRole, 'content' => trim($messagesContent)];
                    }

                    $messagesContent = $msgChat->msg . "\n";
                    $previousRole = $presentRole;
                } else {
                    $messagesContent .= $msgChat->msg . "\n";
                }
            }

            if (!empty($messagesContent)) {

                if ($presentRole == 'assistant') {
                    $chat = \erLhcoreClassModelChat::fetch($chatId);
                    $messagesContent = str_replace([' '.$chat->plain_user_name,' '.$chat->user->name],' <employee>',$messagesContent);
                    if (is_object($chat->online_user) && is_object($chat->online_user->operator_user)) {
                        $messagesContent = str_replace([' '.$chat->online_user->operator_user->name_support,' '.$chat->online_user->operator_user->name],' <employee>',$messagesContent);
                    }
                }

                if (($visitorMessageFirst === true) || ($presentRole == 'user')) {
                    $messagesProcess[$chatId][] = ['role' => $presentRole, 'content' => trim($messagesContent)];
                }
            }
        }

        for ($i = 0; $i < 1; $i++)
        {
            foreach ($messagesProcess as $chatId => $msg) {
                if (!empty($msg)) {
                    $answer = self::getAnswer($msg, $chatId);
                    if ($answer['found'] == true) {
                        if (!isset($suggestions[$chatId])) {
                            $answer['msg'] = str_replace('<employee>','{args.chat.plain_user_name}', $answer['msg']);
                            $chat = \erLhcoreClassModelChat::fetch($chatId);
                            $params['chat'] = $chat;
                            $answer['msg'] = \erLhcoreClassGenericBotWorkflow::translateMessage($answer['msg'], array('chat' => $chat, 'args' => $params));
                            $conversation = "";
                            foreach ($msg as $value) {
                                $conversation .=  strtoupper($value['role']) . ": " . trim($value['content'])."\n";
                            }
                            $suggestions[$chatId][] = array('aid' => $answer['aid'], 'a' => $answer['msg'], 'q' => $conversation);
                        }
                    }
                }
            }
        }

        return $suggestions;
    }

}
