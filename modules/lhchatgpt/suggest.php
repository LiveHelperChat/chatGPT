<?php

session_write_close();

erLhcoreClassChat::validateFilterIn($Params['user_parameters_unordered']['id']);

if (!empty($Params['user_parameters_unordered']['id'])) {
    try {
        $sjOptions = \erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
        $data = (array)$sjOptions->data;
        $suggestions = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::suggestByIds($Params['user_parameters_unordered']['id'],($Params['user_parameters_unordered']['chat'] == 1 ? true : false));
        echo json_encode(array('sg' => $suggestions, 'keep_previous' => isset($data['keep_previous']) && $data['keep_previous'] == 1 ? true : false));
    } catch (Exception $e) {
        if (erConfigClassLhConfig::getInstance()->getSetting('site', 'debug_output')) {
            throw $e;
        }
    }
}

exit;
?>