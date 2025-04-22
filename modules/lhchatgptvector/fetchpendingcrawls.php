<?php

header('Content-Type: application/json');

try {
    // Get the API key from the chatgpt_suggest settings
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
    
    // Get pending crawls (crawls that need to be updated based on their frequency)
    $pendingCrawls = \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::getPendingCrawls();
    
    $responseData = array(
        'error' => false,
        'result' => array(),
    );
    
    foreach ($pendingCrawls as $crawl) {
        $state = $crawl->getState();
        if (isset($state['url']) && is_string($state['url'])) {
            $state['url'] = preg_split('/\r\n|\r|\n/', $state['url'], -1, PREG_SPLIT_NO_EMPTY);
        } else {
            $state['url'] = [];
        }
        $responseData['result'][] = $state;
    }
    
    echo json_encode($responseData);
    
} catch (Exception $e) {
    $responseData = array(
        'error' => true,
        'message' => $e->getMessage()
    );
    
    http_response_code(403);
    echo json_encode($responseData);
}

exit;
?>