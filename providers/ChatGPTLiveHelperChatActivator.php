<?php

namespace LiveHelperChatExtension\chatgpt\providers;

class ChatGPTLiveHelperChatActivator {

    // Remove SMS
    public static function remove()
    {
        if ($restAPI = \erLhcoreClassModelGenericBotRestAPI::findOne(['filter' => ['name' => 'ChatGPTResponse']])) {
            $restAPI->removeThis();
        }

        if ($botPrevious = \erLhcoreClassModelGenericBotBot::findOne(['filter' => ['name' => 'ChatGPTResponse']])) {
            $botPrevious->removeThis();
        }
    }

    public static function installOrUpdate($params)
    {
        // RestAPI
        $restAPI = \erLhcoreClassModelGenericBotRestAPI::findOne(['filter' => ['name' => 'ChatGPTResponse']]);
        $content = json_decode(file_get_contents('extension/chatgpt/doc/assistant/rest-api.json'),true);

        $content['configuration'] = str_replace('{CHAT_GPT_TOKEN}',isset($params['CHAT_GPT_TOKEN']) ? $params['CHAT_GPT_TOKEN'] : 'CHAT_GPT_TOKEN', $content['configuration']);
        $content['configuration'] = str_replace('{CHAT_GPT_STORAGE_ID}',isset($params['CHAT_GPT_STORAGE_ID']) ? $params['CHAT_GPT_STORAGE_ID'] : 'CHAT_GPT_STORAGE_ID', $content['configuration']);

        if (!$restAPI) {
            $restAPI = new \erLhcoreClassModelGenericBotRestAPI();
        }

        $restAPI->setState($content);
        $restAPI->name = 'ChatGPTResponse';
        $restAPI->saveThis();

        // Bot
        if ($botPrevious = \erLhcoreClassModelGenericBotBot::findOne(['filter' => ['name' => 'ChatGPTResponse']])) {
            $botPrevious->removeThis();
        }

        $botData = \erLhcoreClassGenericBotValidator::importBot(json_decode(file_get_contents('extension/chatgpt/doc/assistant/bot-api.json'),true));
        $botData['bot']->name = 'ChatGPTResponse';
        $botData['bot']->updateThis(['update' => ['name']]);

        foreach ($botData['triggers'] as $trigger) {
            $actions = $trigger->actions_front;
            foreach ($actions as $indexAction  => $action) {
                if (isset($actions[$indexAction]['content']['rest_api']) && is_numeric($actions[$indexAction]['content']['rest_api'])) {
                    $actions[$indexAction]['content']['rest_api'] = $restAPI->id;
                }
            }
            $trigger->actions_front = $actions;
            $trigger->actions = json_encode($actions);
            $trigger->updateThis(['update' => ['actions']]);
        }
    }
}

?>