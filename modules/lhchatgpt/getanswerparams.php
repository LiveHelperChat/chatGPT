<?php

session_write_close();

header('content-type: application/json; charset=utf-8');

$departmentId = 0;

// Check if department_id is provided in the request
if (isset($_POST['department_id']) && is_numeric($_POST['department_id'])) {
    $departmentId = (int)$_POST['department_id'];
}

$params['chat'] = new erLhcoreClassModelChat();
$params['chat']->dep_id = $departmentId;

// Fetch default configuration values
$sjOptions = erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
$data = (array)$sjOptions->data;

// Prefill with defaults from configuration (used when no options are passed)
$defaultVectorId = null;
$defaultSystemPrompt = $data['system_instructions'] ?? null;

// Handle vector storage ID - can be department-specific or default
if (!empty($data['vstorage_id'])) {
    $vectorStorageData = json_decode($data['vstorage_id'], true);
    
    if (is_array($vectorStorageData)) {
        if (isset($vectorStorageData[$departmentId])) {
            $defaultVectorId = $vectorStorageData[$departmentId];
        } else {
            $defaultVectorId = $vectorStorageData['default'] ?? null;
        }
    } else {
        $defaultVectorId = $data['vstorage_id'];
    }
}

// Check for custom canned message replacements (these override defaults if set)
$functions = erLhcoreClassModelCannedMsgReplace::findOne(['filter' => ['identifier' => 'llm_functions']]);
$systemPrompt = erLhcoreClassModelCannedMsgReplace::findOne(['filter' => ['identifier' => 'llm_system_prompt']]);
$vectorId = erLhcoreClassModelCannedMsgReplace::findOne(['filter' => ['identifier' => 'llm_vector_id']]);

$functionsValue = "";
$systemPromptValue = $defaultSystemPrompt;
$vectorIdValue = $defaultVectorId;

if ($functions instanceof erLhcoreClassModelCannedMsgReplace) {
    $functionsValue = trim($functions->getValueReplace($params),",");
    if (!empty($functionsValue)) {
        $functionsValue = '[' . $functionsValue . ']';
    }
}

if ($systemPrompt instanceof erLhcoreClassModelCannedMsgReplace) {
    $retrievedValue = $systemPrompt->getValueReplace($params);
    if (!empty($retrievedValue)) {
        $systemPromptValue = $retrievedValue;
    }
}

if ($vectorId instanceof erLhcoreClassModelCannedMsgReplace) {
    $retrievedValue = $vectorId->getValueReplace($params);
    if (!empty($retrievedValue)) {
        $vectorIdValue = $retrievedValue;
    }
}

$response = [
    'vector_storage_id' => $vectorIdValue,
    'system_prompt' => $systemPromptValue,
    'functions' => !empty($functionsValue) ? json_decode($functionsValue) : null
];

echo json_encode($response);

exit;

?>
