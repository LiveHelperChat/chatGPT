<?php

session_write_close();

header ( 'content-type: application/json; charset=utf-8' );

if ($Params['user_parameters']['action'] == 'answer') {
    $departmentId = 0;
    if (isset($_POST['chat_id'])) {
        $chat = erLhcoreClassModelChat::fetch($_POST['chat_id']);
        $departmentId = is_object($chat) ? $chat->dep_id : 0;
    }

    try {
        $response = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::retrieveMessage($_POST['question'], $departmentId);
        echo json_encode(['response' => erLhcoreClassBBCode::make_clickable($response)]);
    } catch (Exception $e) {
        echo json_encode(['response' => $e->getMessage()]);
    }
}

exit;

?>