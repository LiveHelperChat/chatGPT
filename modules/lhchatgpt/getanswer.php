<?php

session_write_close();

header ( 'content-type: application/json; charset=utf-8' );

if ($Params['user_parameters']['action'] == 'answerbychat') {

    $chat_id = $_POST['chat_id'];
    $chatGPT = \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTChat::findOne(['filter' => ['chat_id' => $chat_id]]);

    if (!is_object($chatGPT)){
        $thread_id = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::getThread($chat_id);
        $chatGPT = new \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTChat();
        $chatGPT->chat_id = $chat_id;
        $chatGPT->thread_id = $thread_id;
        $chatGPT->saveThis();
    } else {
        $thread_id = $chatGPT->thread_id;
    }

    $run_id = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::scheduleRun($chat_id, $thread_id, [['content' => $_POST['question']]]);
    echo json_encode(['thread_id' => $thread_id, 'run_id' => $run_id]);

} else if ($Params['user_parameters']['action'] == 'schedule') {
    $thread_id = erLhcoreClassModelUserSetting::getSetting('chatgpt_thread_id','');
    if (empty($thread_id)) {
        $thread_id = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::getThread();
        erLhcoreClassModelUserSetting::setSetting('chatgpt_thread_id', $thread_id);
    }
    $run_id = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::scheduleRun(0, $thread_id, [['content' => $_POST['question']]]);
    echo json_encode(['thread_id' => $thread_id, 'run_id' => $run_id]);
} else if ($Params['user_parameters']['action'] == 'answer') {
    $status = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::getRunStatus($_POST['thread_id'], $_POST['run_id']);
    if ($status == 'completed') {
        $response = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::retrieveMessage(0, $_POST['thread_id'], $_POST['run_id']);
        echo json_encode(['status' => $status, 'response' => erLhcoreClassBBCode::make_clickable($response)]);
    } else {
        echo json_encode(['status' => $status]);
    }
}

exit;

?>