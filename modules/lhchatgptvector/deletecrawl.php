<?php

if (!$currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
    die('Invalid CSFR Token');
    exit;
}

$crawl = \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::fetch($Params['user_parameters']['crawl_id']);

if ($crawl->file_id != '') {
    \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteFileFromVectorStorage($crawl->vector_storage_id, $crawl->file_id);

    \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteFile($crawl->file_id);
}

$crawl->removeThis();

erLhcoreClassModule::redirect('chatgptvector/view','/'.$Params['user_parameters']['vector_storage_id']);
exit;

?>