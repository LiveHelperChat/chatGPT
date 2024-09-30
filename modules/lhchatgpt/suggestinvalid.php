<?php

try {

    $chat =  erLhcoreClassModelChat::fetch($Params['user_parameters']['id']);

    if ($chat instanceof erLhcoreClassModelChat) {

        if (isset($_POST['aid'])) {
            $aid = $_POST['aid'];
        } else {
            throw new Exception('Answer ID has to be provided!');
        }

        $audit = erLhAbstractModelAudit::fetch($aid);
        $data = json_decode($audit->message,true);

        $invalid = new \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTInvalid();
        $invalid->aid = $aid;
        $invalid->question = json_encode($data['payload'],JSON_PRETTY_PRINT);
        $invalid->answer = json_encode($data['content'],JSON_PRETTY_PRINT);
        $invalid->chat_id = $chat->id;
        $invalid->user_id = $currentUser->getUserID();
        $invalid->saveThis();

        echo json_encode(array('error' => false, 'msg' => 'Report processed!'));
    } else {
        throw new Exception('Chat not found!');
    }

} catch (Exception $e) {
    echo json_encode(array('error' => true, 'msg' => $e->getMessage()));
}

exit;

?>