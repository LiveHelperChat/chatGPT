<?php

use LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl;
use LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatCrawlValidator;


$tpl = erLhcoreClassTemplate::getInstance('lhchatgptvector/newcrawl.tpl.php');

$item = new erLhcoreClassModelChatGPTCrawl();
$item->vector_storage_id = $Params['user_parameters']['id'];

if (ezcInputForm::hasPostData()) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('chatgptvector/view','/' . $Params['user_parameters']['id']);
        exit;
    }

    // Apply additional validation
    $Errors = ChatGPTLiveHelperChatCrawlValidator::validateCrawl($item);

    if (empty($Errors)) {
        $item->saveThis();
        $tpl->set('saved', true);
    } else {
        $tpl->set('errors', $Errors);
    }
}

$tpl->set('item', $item);
$tpl->set('storage_id', $Params['user_parameters']['id']);

echo $tpl->fetch();
exit;

?>