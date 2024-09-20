<?php

namespace LiveHelperChatExtension\chatgpt\providers;

class ChatGPTLiveHelperChatActivator {

    // Remove SMS
    public static function remove()
    {

        if ($restAPI = \erLhcoreClassModelGenericBotRestAPI::findOne(['filter' => ['name' => 'ChatGPTAssistant']])) {
            $restAPI->removeThis();
        }

        if ($botPrevious = \erLhcoreClassModelGenericBotBot::findOne(['filter' => ['name' => 'ChatGPTAssistant']])) {
            $botPrevious->removeThis();
        }
    }

    public static function installOrUpdate($params)
    {
        // RestAPI
        $restAPI = \erLhcoreClassModelGenericBotRestAPI::findOne(['filter' => ['name' => 'ChatGPTAssistant']]);
        $content = json_decode(file_get_contents('extension/chatgpt/doc/assistant/rest-api.json'),true);

        $content['configuration'] = str_replace('{CHAT_GPT_TOKEN}',isset($params['CHAT_GPT_TOKEN']) ? $params['CHAT_GPT_TOKEN'] : 'CHAT_GPT_TOKEN', $content['configuration']);
        $content['configuration'] = str_replace('{CHATGPT_ASSISTANT_ID}',isset($params['CHATGPT_ASSISTANT_ID']) ? $params['CHATGPT_ASSISTANT_ID'] : 'CHATGPT_ASSISTANT_ID', $content['configuration']);

        if (!$restAPI) {
            $restAPI = new \erLhcoreClassModelGenericBotRestAPI();
        }

        $restAPI->setState($content);
        $restAPI->name = 'ChatGPTAssistant';
        $restAPI->saveThis();

        // Bot
        if ($botPrevious = \erLhcoreClassModelGenericBotBot::findOne(['filter' => ['name' => 'ChatGPTAssistant']])) {
            $botPrevious->removeThis();
        }

        $botData = \erLhcoreClassGenericBotValidator::importBot(json_decode(file_get_contents('extension/chatgpt/doc/assistant/bot-api.json'),true));
        $botData['bot']->name = 'ChatGPTAssistant';
        $botData['bot']->updateThis(['update' => ['name']]);

        // Send Question Trigger
        $trigger = $botData['triggers'][1];
        $actions = $trigger->actions_front;
        $actions[0]['content']['rest_api'] = $restAPI->id;
        $trigger->actions_front = $actions;
        $trigger->actions = json_encode($actions);
        $trigger->updateThis(['update' => ['actions']]);

        // New thread
        $trigger = $botData['triggers'][2];
        $actions = $trigger->actions_front;
        $actions[0]['content']['rest_api'] = $restAPI->id;
        $trigger->actions_front = $actions;
        $trigger->actions = json_encode($actions);
        $trigger->updateThis(['update' => ['actions']]);

        // Wait job completion
        $trigger = $botData['triggers'][5];
        $actions = $trigger->actions_front;
        $actions[1]['content']['rest_api'] = $restAPI->id;
        $trigger->actions_front = $actions;
        $trigger->actions = json_encode($actions);
        $trigger->updateThis(['update' => ['actions']]);

        // Retrieve response
        $trigger = $botData['triggers'][7];
        $actions = $trigger->actions_front;
        $actions[0]['content']['rest_api'] = $restAPI->id;
        $trigger->actions_front = $actions;
        $trigger->actions = json_encode($actions);
        $trigger->updateThis(['update' => ['actions']]);

        // Requires actions
        $trigger = $botData['triggers'][8];
        $actions = $trigger->actions_front;
        $actions[2]['content']['rest_api'] = $restAPI->id;
        $trigger->actions_front = $actions;
        $trigger->actions = json_encode($actions);
        $trigger->updateThis(['update' => ['actions']]);

    }
}

?>