<?php

if (!$currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
    die('Invalid CSFR Token');
    exit;
}

// Delete all vector storage files
foreach(\LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::listFiles($Params['user_parameters']['id'])['data'] as $storeFile) {
    // Delete association
    \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteFileFromVectoreStorage($Params['user_parameters']['id'], $storeFile['id']);

    // Delete file
    \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteFile($storeFile['id']);
}

\LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteStorage($Params['user_parameters']['id']);

erLhcoreClassModule::redirect('chatgptvector/list');
exit;

?>