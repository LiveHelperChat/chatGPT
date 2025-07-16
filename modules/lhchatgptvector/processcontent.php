<?php

use LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl;

// Check for CSRF token
if (!$currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
    die('Invalid CSFR Token');
    exit;
}

// Fetch the crawl by ID
$crawl = erLhcoreClassModelChatGPTCrawl::fetch($Params['user_parameters']['crawl_id']);

if (!($crawl instanceof erLhcoreClassModelChatGPTCrawl)) {
    erLhcoreClassModule::redirect('chatgptvector/view', '/' . $Params['user_parameters']['storage_id']);
    exit;
}

// Check if this is a content type crawl
if ($crawl->type != erLhcoreClassModelChatGPTCrawl::TYPE_CONTENT) {
    erLhcoreClassModule::redirect('chatgptvector/view', '/' . $Params['user_parameters']['storage_id']);
    exit;
}

// Check if content is empty
if (empty($crawl->content)) {
    erLhcoreClassModule::redirect('chatgptvector/view', '/' . $Params['user_parameters']['storage_id']);
    exit;
}

try {
    // Update status to processing
    $crawl->status = erLhcoreClassModelChatGPTCrawl::STATUS_CRAWLING;
    $crawl->saveThis();

    // If a file is already associated with this crawl, remove it first
    if (!empty($crawl->file_id)) {
        // Remove from vector storage
        \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteFileFromVectorStorage(
            $crawl->vector_storage_id,
            $crawl->file_id
        );
        
        // Delete the file from OpenAI
        \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteFile($crawl->file_id);
    }

    // Generate a filename for the content
    $filename = preg_replace('/[^a-zA-Z0-9_-]/', '_', $crawl->name) . '.txt';
    
    // Upload content as a file to OpenAI
    $responseFile = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::uploadFile(
        $crawl->content,
        $filename,
        'assistants',
        true // This indicates it's raw content, not a file path
    );

    if (isset($responseFile['id'])) {
        // Attach the file to the vector storage
        $response = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::attachFileToStorage(
            $crawl->vector_storage_id,
            $responseFile['id']
        );

        if (isset($response['error']['message'])) {
            // If attaching failed, delete the uploaded file
            \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteFile($responseFile['id']);
            throw new Exception($response['error']['message']);
        } else {
            // Success - update the crawl record
            $crawl->file_id = $responseFile['id'];
            $crawl->status = erLhcoreClassModelChatGPTCrawl::STATUS_COMPLETED;
            $crawl->last_crawled_at = time();
            $crawl->saveThis();
        }
    } else {
        throw new Exception('Upload file failed! ' . json_encode($responseFile));
    }

} catch (Exception $e) {
    // Update status to error
    $crawl->status = erLhcoreClassModelChatGPTCrawl::STATUS_ERROR;
    $crawl->saveThis();
    
    // You could log the error here if needed
    error_log('ChatGPT Content Processing Error: ' . $e->getMessage());
}

// Redirect back to the vector storage view
erLhcoreClassModule::redirect('chatgptvector/view', '/' . $Params['user_parameters']['storage_id']);
exit;

?>
