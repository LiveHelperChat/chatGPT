<?php

use LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl;

// Check for CSRF token
if (!$currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
    die('Invalid CSFR Token');
    exit;
}

// Fetch the crawl by ID
$crawl = erLhcoreClassModelChatGPTCrawl::fetch($Params['user_parameters']['crawl_id']);

if ($crawl instanceof erLhcoreClassModelChatGPTCrawl) {
    // Reset crawl status to IDLE
    $crawl->status = erLhcoreClassModelChatGPTCrawl::STATUS_IDLE;
    
    // Reset last crawled timestamp
    $crawl->last_crawled_at = 0;
    
    // Save changes
    $crawl->saveThis();
}

// Redirect back to the vector storage view
erLhcoreClassModule::redirect('chatgptvector/view', '/' . $Params['user_parameters']['storage_id']);
exit;
?>

