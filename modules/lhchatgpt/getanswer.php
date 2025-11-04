<?php

session_write_close();

header ( 'content-type: application/json; charset=utf-8' );

if ($Params['user_parameters']['action'] == 'answer') {
    $departmentId = 0;
    
    $canOverride = erLhcoreClassUser::instance()->hasAccessTo('lhchatgptvector','configure');

    // Check if department_id is provided in the request
    if (isset($_POST['department_id']) && is_numeric($_POST['department_id'])) {
        $departmentId = (int)$_POST['department_id'];
    } elseif (isset($_POST['chat_id'])) {
        $chat = erLhcoreClassModelChat::fetch($_POST['chat_id']);
        $departmentId = is_object($chat) ? $chat->dep_id : 0;
    }

    // Prepare optional parameters
    $options = [];
    
    // Add vector_storage_id if provided
    if ($canOverride && isset($_POST['vector_storage_id']) && !empty($_POST['vector_storage_id'])) {
        $options['vector_storage_id'] = $_POST['vector_storage_id'];
    }
    
    // Add system_prompt if provided
    if ($canOverride && isset($_POST['system_prompt']) && !empty($_POST['system_prompt'])) {
        $options['system_prompt'] = $_POST['system_prompt'];
    }
    
    // Add functions if provided
    if ($canOverride && isset($_POST['functions']) && !empty($_POST['functions'])) {
        // If functions is a string, decode it
        if (is_string($_POST['functions'])) {
            $functions = json_decode($_POST['functions']);
            if (json_last_error() === JSON_ERROR_NONE && is_array($functions)) {
                $options['llm_functions'] = $functions;
            }
        } elseif (is_array($_POST['functions'])) {
            $options['llm_functions'] = $_POST['functions'];
        }
    }
    
    // Prepare messages array with history
    $messages = [];
    
    // Add message history if provided
    if ($canOverride && isset($_POST['message_history']) && !empty($_POST['message_history'])) {
        // If message_history is a string, decode it
        if (is_string($_POST['message_history'])) {
            $messageHistory = json_decode($_POST['message_history'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($messageHistory)) {
                $messages = $messageHistory;
            }
        } elseif (is_array($_POST['message_history'])) {
            $messages = $_POST['message_history'];
        }
    }
    
    // Add the current question as a user message
    $messages[] = [
        'role' => 'user',
        'content' => $_POST['question']
    ];

    if ($canOverride && isset($_POST['debug_mode']) && !empty($_POST['debug_mode'])) {
        $options['debug_mode'] = true;
    }

    try {
        $response = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::retrieveMessage($messages, $departmentId, $options);
        
        if ($canOverride && isset($_POST['debug_mode']) && !empty($_POST['debug_mode'])) {
            echo json_encode(['response' => (\LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::$responseType == 'text' ? erLhcoreClassBBCode::make_clickable($response) : $response), 'response_type' => \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::$responseType, 'request_log' => \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::$requestLog]);
        } else {
            echo json_encode(['response' => erLhcoreClassBBCode::make_clickable($response)]);
        }

    } catch (Exception $e) {
        if ($canOverride && isset($_POST['debug_mode']) && !empty($_POST['debug_mode'])) {
            echo json_encode(['response' => $e->getMessage(), 'request_log' => \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatValidator::$requestLog]);
        } else {
            echo json_encode(['response' => $e->getMessage()]);
        }
    }
}

exit;

?>