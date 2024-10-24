<?php

namespace LiveHelperChatExtension\chatgpt\providers;

class ChatGPTLiveHelperChatValidator {

    public static $requestLog = [];

    public static function getThread($chat_id = 0)
    {
        $sjOptions = \erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
        $data = (array)$sjOptions->data;

        if ($chat_id > 0) {
            $chat = \erLhcoreClassModelChat::fetch($chat_id);
        } else {
            $chat = new \erLhcoreClassModelChat();
        }

        if ($restAPI = \erLhcoreClassModelGenericBotRestAPI::findOne(['filter' => ['name' => 'ChatGPTAssistant']])) {
            foreach ($restAPI->configuration_array['parameters'] as $key => $value) {
                if (isset($value['name']) && $value['name'] == 'CreateThread') {
                    $actionTrigger = json_decode('
    {
        "_id": "82zB5bMuC",
        "type": "restapi",
        "content": {
        "text": "",
            "rest_api": ' . $restAPI->id . ',
            "rest_api_method": "'. $value['id'] .'",
            "rest_api_method_params": {},
            "rest_api_method_output": {
                "'. $value['output'][0]['id'] .'": "0",
                "default_trigger": "0"
            },
            "attr_options": {"no_body": true,"background_process": false}}}',true);

                    $restAPI->configuration_array['parameters'][$key]['auth_bearer'] = $data['project_api_key'];
                    $output = \erLhcoreClassGenericBotActionRestapi::process($chat, $actionTrigger,null, ['rest_api_object' => $restAPI]);

                    if (isset($output['replace_array']['{content_1}'])) {
                        return $output['replace_array']['{content_1}'];
                    }

                    self::$requestLog[] = $output;

                    throw new \Exception('Something went wrong while processing your request');
                }
            }
        }
        throw new \Exception('Thread could not be created!');
    }

    public static function scheduleRun($chat_id, $thread_id, $questions) {
        $sjOptions = \erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
        $data = (array)$sjOptions->data;

        if ($chat_id > 0) {
            $chat = \erLhcoreClassModelChat::fetch($chat_id);
        } else {
            $chat = new \erLhcoreClassModelChat();
        }

        if ($restAPI = \erLhcoreClassModelGenericBotRestAPI::findOne(['filter' => ['name' => 'ChatGPTAssistant']])) {
            foreach ($restAPI->configuration_array['parameters'] as $key => $value) {
                if (isset($value['name']) && $value['name'] == 'ScheduleRun') {
                    $actionTrigger = json_decode('
    {
        "_id": "82zB5bMuC",
        "type": "restapi",
        "content": {
        "text": "",
            "rest_api": ' . $restAPI->id . ',
            "rest_api_method": "'. $value['id'] .'",
            "rest_api_method_params": {},
            "rest_api_method_output": {
                "'. $value['output'][0]['id'] .'": "0",
                "default_trigger": "0"
            },
            "attr_options": {"no_body": true,"background_process": false}}}',true);

                    $restAPI->configuration_array['parameters'][$key]['body_raw'] = '{"assistant_id": "' . $data['assistant_id'] . '","additional_messages":[{"role":"user","content":{{msg_url}} }]}';
                    $restAPI->configuration_array['parameters'][$key]['auth_bearer'] = $data['project_api_key'];
                    $restAPI->configuration_array['parameters'][$key]['suburl'] = str_replace(
                        [
                            '{{args.chat.chat_variables_array.chatgpt_thread_id}}'
                        ],
                        [
                            $thread_id
                        ],
                        $restAPI->configuration_array['parameters'][$key]['suburl']
                    );

                    $output = \erLhcoreClassGenericBotActionRestapi::process($chat,$actionTrigger,null, ['msg_text' => $questions[0]['content'], 'rest_api_object' => $restAPI]);

                    if (isset($output['replace_array']['{content_1}'])) {
                        return $output['replace_array']['{content_1}'];
                    }

                    self::$requestLog[] = $output;

                    throw new \Exception('Something went wrong while processing your request');
                }
            }
        }
        throw new \Exception('Thread could not be created!');
    }

    public static function waitCompletion($chat_id, $thread_id, $run_id) {
        $sjOptions = \erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
        $data = (array)$sjOptions->data;
        $chat = \erLhcoreClassModelChat::fetch($chat_id);

        if ($restAPI = \erLhcoreClassModelGenericBotRestAPI::findOne(['filter' => ['name' => 'ChatGPTAssistant']])) {
            foreach ($restAPI->configuration_array['parameters'] as $key => $value) {
                if (isset($value['name']) && $value['name'] == 'CheckStatusRun') {
                    $actionTrigger = json_decode('
    {
        "_id": "82zB5bMuC",
        "type": "restapi",
        "content": {
        "text": "",
            "rest_api": ' . $restAPI->id . ',
            "rest_api_method": "'. $value['id'] .'",
            "rest_api_method_params": {},
            "rest_api_method_output": {
                "'. $value['output'][0]['id'] .'": "0",
                "'. $value['output'][1]['id'] .'": "0",
                "default_trigger": "0"
            },
            "attr_options": {"no_body": true,"background_process": false}}}',true);

                    $restAPI->configuration_array['parameters'][$key]['auth_bearer'] = $data['project_api_key'];
                    $restAPI->configuration_array['parameters'][$key]['suburl'] = str_replace(
                        [
                        '{{args.chat.chat_variables_array.chatgpt_thread_id}}',
                        '{{args.chat.chat_variables_array.chatgpt_run_id}}'
                        ],
                        [
                            $thread_id,
                            $run_id
                        ],
                        $restAPI->configuration_array['parameters'][$key]['suburl']
                    );

                    $output = \erLhcoreClassGenericBotActionRestapi::process($chat,$actionTrigger,null, [ 'rest_api_object' => $restAPI]);

                    if (isset($output['replace_array']['{content_1}']) && $output['replace_array']['{content_1}'] == 'completed') {
                        return true;
                    } else {
                        self::$requestLog[] = $output;
                        return false;
                    }
                }
            }
        }
        throw new \Exception('Thread could not be created!');
    }

    public static function getRunStatus($thread_id, $run_id)
    {
        $sjOptions = \erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
        $data = (array)$sjOptions->data;

        $response = json_decode(\erLhcoreClassModelChatOnlineUser::executeRequest('https://api.openai.com/v1/threads/' . $thread_id . '/runs/' . $run_id,
        [
            'Content-Type: application/json',
            'OpenAI-Beta: assistants=v2',
            'Authorization: Bearer ' . $data['project_api_key']
        ]
        ),true);

        if (isset($response['status'])) {
            return $response['status'];
        }

        throw new \Exception('Could not retrieve status!');
    }

    public static function retrieveMessage($chat_id, $thread_id, $run_id)
    {
        $sjOptions = \erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
        $data = (array)$sjOptions->data;

        if ($chat_id > 0) {
            $chat = \erLhcoreClassModelChat::fetch($chat_id);
        } else {
            $chat = new \erLhcoreClassModelChat();
        }

        if ($restAPI = \erLhcoreClassModelGenericBotRestAPI::findOne(['filter' => ['name' => 'ChatGPTAssistant']])) {
            foreach ($restAPI->configuration_array['parameters'] as $key => $value) {
                if (isset($value['name']) && $value['name'] == 'RetrieveMessage') {
                    $actionTrigger = json_decode('
    {
        "_id": "82zB5bMuC",
        "type": "restapi",
        "content": {
        "text": "",
            "rest_api": ' . $restAPI->id . ',
            "rest_api_method": "'. $value['id'] .'",
            "rest_api_method_params": {},
            "rest_api_method_output": {
                "'. $value['output'][0]['id'] .'": "0",
                "default_trigger": "0"
            },
            "attr_options": {"no_body": true,"background_process": false}}}',true);

                    $restAPI->configuration_array['parameters'][$key]['auth_bearer'] = $data['project_api_key'];
                    $restAPI->configuration_array['parameters'][$key]['suburl'] = str_replace(
                        [
                            '{{args.chat.chat_variables_array.chatgpt_thread_id}}',
                            '{{args.chat.chat_variables_array.chatgpt_run_id}}'
                        ],
                        [
                            $thread_id,
                            $run_id,
                        ],
                        $restAPI->configuration_array['parameters'][$key]['suburl']
                    );

                    $output = \erLhcoreClassGenericBotActionRestapi::process($chat,$actionTrigger,null, [ 'rest_api_object' => $restAPI]);

                    if (isset($output['replace_array']['{content_1}'])) {
                        return $output['replace_array']['{content_1}'];
                    }

                    self::$requestLog[] = $output;

                    throw new \Exception('Something went wrong while processing your request');
                }
            }
        }
        throw new \Exception('Thread could not be created!');
    }

    public static function getAnswer($questions, $chat_id)
    {
        $sjOptions = \erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
        $data = (array)$sjOptions->data;

        $chatGPT = \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTChat::findOne(['filter' => ['chat_id' => $chat_id]]);

        if (!is_object($chatGPT)){
            $thread_id = self::getThread($chat_id);
            $chatGPT = new \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTChat();
            $chatGPT->chat_id = $chat_id;
            $chatGPT->thread_id = $thread_id;
            $chatGPT->saveThis();
        } else {
            $thread_id = $chatGPT->thread_id;
        }

        try {
            $run_id = self::scheduleRun($chat_id, $thread_id, $questions);

            $waitForCompletion = self::waitCompletion($chat_id, $thread_id, $run_id); // Finished in success

            $response = ['found' => false, 'msg' => '', 'aid' => null];

            if ($waitForCompletion === true) {
                $response['found'] = true;
                $response['msg'] = self::retrieveMessage($chat_id, $thread_id, $run_id);
            } else {
                $response['found'] = false;
            }
        } catch (\Exception $e) {
            $response['found'] = false;
            $response['error'] = $e->getMessage();
        }

        $lastInsertId = 0;

        if (isset($data['log_request']) && $data['log_request'] == 1) {

            $db = \ezcDbInstance::get();
            $db->beginTransaction();

            \erLhcoreClassLog::write(json_encode([
                'payload' => $questions,
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
