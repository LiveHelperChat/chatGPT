<?php

session_write_close();

header ( 'content-type: application/json; charset=utf-8' );

if ($Params['user_parameters']['action'] == 'answer') {
    $response = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::retrieveMessage($_POST['question']);
    echo json_encode(['response' => erLhcoreClassBBCode::make_clickable($response)]);
}

exit;

?>