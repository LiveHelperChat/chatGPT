<?php

use LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl;
use LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatCrawlValidator;

$tpl = erLhcoreClassTemplate::getInstance('lhchatgptvector/editcrawl.tpl.php');

$item = erLhcoreClassModelChatGPTCrawl::fetch($Params['user_parameters']['crawl_id']);

if (!$item) {
    erLhcoreClassModule::redirect('chatgptvector/view', '/' . $Params['user_parameters']['vector_storage_id']);
    exit;
}

if (ezcInputForm::hasPostData()) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('chatgptvector/view', '/' . $Params['user_parameters']['vector_storage_id']);
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
$tpl->set('storage_id', $Params['user_parameters']['vector_storage_id']);

echo $tpl->fetch();
exit;

?>