<?php

header('Content-Type: application/json');

$response = array();

// Check if required POST parameters exist
if (!isset($_POST['crawl_id']) || !isset($_POST['status']) || !isset($_POST['number_of_pages'])) {
    echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    exit;
}

$crawlId = (int)$_POST['crawl_id'];
$status = (int)$_POST['status'];
$number_of_pages = (int)$_POST['number_of_pages'];

$db = ezcDbInstance::get();
$db->beginTransaction();
try {

    $chatgpt_suggest = erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
    $data = (array)$chatgpt_suggest->data;

    $crawl_api_key = isset($data['crawl_api_key']) ? $data['crawl_api_key'] : '';

    if (empty($crawl_api_key)) {
        throw new Exception('Crawl API key is not configured');
    }

    // Check if API key is provided and matches the configured key
    $provided_key = isset($_SERVER['HTTP_X_API_KEY']) ? $_SERVER['HTTP_X_API_KEY'] : '';

    if (empty($provided_key) || $provided_key !== $crawl_api_key) {
        throw new Exception('Invalid API key');
    }

    $crawl = \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::fetch($crawlId);
    
    if (!$crawl instanceof \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl) {
        throw new Exception('Crawl not found!');
    }

    $crawl->status = $status;
    $crawl->number_of_pages = $number_of_pages;

    // If status is completed, we expect a file upload
    if ($status == \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::STATUS_COMPLETED) {
        if (isset($_FILES['file']) && $_FILES['file']['error'] == UPLOAD_ERR_OK) {
            // Read file content
            $fileContent = file_get_contents($_FILES['file']['tmp_name']);
            if ($fileContent === false) {
                throw new Exception('Failed to read uploaded file');
            }

            // Get file name from POST or use the uploaded filename
            $fileName = (!empty($_POST['file_name'])) ? $_POST['file_name'] : $crawl->name . '.md';

            // Use the ChatGPTLiveHelperChatCrawlValidator to handle file management
            $success = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatCrawlValidator::updateCrawlStatus($crawl, $fileContent, $fileName);

            if (!$success) {
                throw new Exception('Failed to update crawl status with file');
            }
        } else {
            throw new Exception('File upload is required when status is completed!');
        }
    } else {
        // For non-completed statuses, just save the crawl status
        $crawl->saveThis();
    }

    $db->commit();
    
    $response['success'] = true;
    $response['crawl_id'] = $crawlId;
    $response['message'] = 'Crawl status updated successfully';
    if (!empty($crawl->file_id)) {
        $response['file_id'] = $crawl->file_id;
    }
    
} catch(Exception $e) {
    $db->rollback();
    $response['success'] = false;
    $response['error'] = $e->getMessage();
}

echo json_encode($response);
exit;
?>