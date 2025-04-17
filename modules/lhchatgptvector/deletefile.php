<?php

if (!$currentUser->validateCSFRToken($Params['user_parameters_unordered']['csfr'])) {
    die('Invalid CSFR Token');
    exit;
}

\LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteFileFromVectoreStorage($Params['user_parameters']['vector_storage_id'], $Params['user_parameters']['file_id']);

\LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteFile($Params['user_parameters']['file_id']);

erLhcoreClassModule::redirect('chatgptvector/view','/'.$Params['user_parameters']['vector_storage_id']);
exit;

?>