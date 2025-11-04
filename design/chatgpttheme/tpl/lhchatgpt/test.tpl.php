<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','OpenAI Test'); ?></h1>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('system/etracking', 'Department')?></label>
                <?php
                $params = array (
                    'input_name'     => 'department_id',
                    'display_name'   => 'name',
                    'css_class'      => 'form-control form-control-sm',
                    'selected_id'    => 0,
                    'default_value'  => 0,
                    'optional_field' => erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Choose department to load parameters'),
                    'list_function'  => 'erLhcoreClassModelDepartament::getList',
                    'list_function_params' => array('limit' => false)
                );
                echo erLhcoreClassRenderHelper::renderCombobox( $params ); ?>
            </div>

            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Message History');?></label>
                <div id="message-history-container" class="border rounded p-2 mb-2" style="max-height: 300px; overflow-y: auto; background-color: #f8f9fa;">
                    <small class="text-muted"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','No messages yet. Add messages below to build conversation history.');?></small>
                </div>
                <div class="btn-group btn-group-sm mb-2" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="add-user-message">
                        <i class="material-icons" style="font-size: 14px; vertical-align: middle;">person</i>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Add User Message');?>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-success" id="add-assistant-message">
                        <i class="material-icons" style="font-size: 14px; vertical-align: middle;">smart_toy</i>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Add Assistant Message');?>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="clear-history">
                        <i class="material-icons" style="font-size: 14px; vertical-align: middle;">clear</i>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Clear History');?>
                    </button>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="do-not-populate-history">
                    <label class="form-check-label" for="do-not-populate-history">
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Do not populate chat history');?>
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Enter your question');?></label>
                <textarea class="form-control form-control-sm" id="chatgpt-question" rows="5"></textarea>
            </div>
        </div>

        <div class="col-6">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Vector Storage ID');?></label>
                <input type="text" class="form-control form-control-sm" id="chatgpt-vector-storage-id" placeholder="Optional - Enter vector storage ID">
            </div>

            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','System Prompt');?></label>
                <textarea class="form-control form-control-sm" id="chatgpt-system-prompt" rows="3" placeholder="Optional - Enter custom system prompt"></textarea>
            </div>

            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Functions');?></label>
                <textarea class="form-control form-control-sm" id="chatgpt-functions" rows="5" placeholder="Optional - Enter functions as JSON array"></textarea>
            </div>
        </div>
    </div>
   
    <div class="btn-group" role="group" aria-label="Basic example">
        <button id="send-question" type="button" class="btn btn-sm btn-info"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Send'); ?></button>
    </div>

    <div id="chatgpt-answer" class="py-1 text-muted"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Response will appear here...');?></div>
  

</form>

<script>
    (function () {
        // Message history array
        let messageHistory = [];
        let messageCounter = 0;

        // Function to render message history
        function renderMessageHistory() {
            const container = $('#message-history-container');
            
            if (messageHistory.length === 0) {
                container.html('<small class="text-muted">No messages yet. Add messages below to build conversation history.</small>');
                return;
            }

            let html = '';
            messageHistory.forEach(function(msg, index) {
                const bgColor = msg.role === 'user' ? '#e3f2fd' : '#e8f5e9';
                const icon = msg.role === 'user' ? 'person' : 'smart_toy';
                const roleLabel = msg.role === 'user' ? 'User' : 'Assistant';
                const textColor = msg.role === 'user' ? '#1976d2' : '#388e3c';
                
                html += '<div class="message-item mb-2 p-2 rounded" style="background-color: ' + bgColor + '; position: relative;">';
                html += '<div class="d-flex align-items-start">';
                html += '<i class="material-icons me-2" style="font-size: 16px; color: ' + textColor + ';">' + icon + '</i>';
                html += '<div class="flex-grow-1">';
                html += '<strong style="color: ' + textColor + '; font-size: 12px;">' + roleLabel + '</strong>';
                html += '<div class="mt-1" style="font-size: 13px; white-space: pre-wrap; word-wrap: break-word;">' + escapeHtml(msg.content) + '</div>';
                html += '</div>';
                html += '<button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" onclick="removeMessage(' + index + ')" style="line-height: 1;">';
                html += '<i class="material-icons" style="font-size: 16px;">close</i>';
                html += '</button>';
                html += '</div>';
                html += '</div>';
            });
            
            container.html(html);
            // Scroll to bottom
            container.scrollTop(container[0].scrollHeight);
        }

        // Function to escape HTML
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Make removeMessage global so it can be called from onclick
        window.removeMessage = function(index) {
            messageHistory.splice(index, 1);
            renderMessageHistory();
        };

        // Add user message
        $('#add-user-message').click(function() {
            const content = prompt('Enter user message:');
            if (content && content.trim()) {
                messageHistory.push({
                    role: 'user',
                    content: content.trim()
                });
                renderMessageHistory();
            }
        });

        // Add assistant message
        $('#add-assistant-message').click(function() {
            const content = prompt('Enter assistant message:');
            if (content && content.trim()) {
                messageHistory.push({
                    role: 'assistant',
                    content: content.trim()
                });
                renderMessageHistory();
            }
        });

        // Clear history
        $('#clear-history').click(function() {
            if (messageHistory.length > 0 && confirm('Are you sure you want to clear the entire message history?')) {
                messageHistory = [];
                renderMessageHistory();
            }
        });

        // Function to load department parameters (vector storage id, system prompt, functions)
        function loadDepartmentParams(silent) {
            silent = !!silent;
            let departmentId = document.getElementById('id_department_id').value;

            if (!departmentId) {
                if (!silent) {
                    alert('Please select a department first!');
                }
                return;
            }

            const $dropdown = $('#id_department_id');
            $dropdown.prop('disabled', true);

            $.postJSON(WWW_DIR_JAVASCRIPT + 'chatgpt/getanswerparams', {
                'department_id': departmentId
            }).done(function(data){
                // Populate the fields with the returned data
                if (data.vector_storage_id) {
                    $('#chatgpt-vector-storage-id').val(data.vector_storage_id);
                } else {
                    $('#chatgpt-vector-storage-id').val('');
                }

                if (data.system_prompt) {
                    $('#chatgpt-system-prompt').val(data.system_prompt);
                } else {
                    $('#chatgpt-system-prompt').val('');
                }

                if (data.functions) {
                    try {
                        $('#chatgpt-functions').val(JSON.stringify(data.functions, null, 2));
                    } catch (e) {
                        // if functions is a string already
                        $('#chatgpt-functions').val(data.functions);
                    }
                } else {
                    $('#chatgpt-functions').val('');
                }
            }).fail(function(){
                if (!silent) {
                    alert('Failed to load parameters');
                }
            }).always(function(){
                $dropdown.prop('disabled', false);
            });
        }

        // Call loader on change (user-triggered)
        $('#id_department_id').change(function(){
            loadDepartmentParams(false);
        });

        // Also attempt to load parameters on page load silently
        $(document).ready(function() {
            // silent=true will not alert if no department is selected
            loadDepartmentParams(true);
        });

        $('#send-question').click(function(){
            let question = document.getElementById('chatgpt-question').value;
            let departmentId = document.getElementById('id_department_id').value;
            let vectorStorageId = document.getElementById('chatgpt-vector-storage-id').value;
            let systemPrompt = document.getElementById('chatgpt-system-prompt').value;
            let functions = document.getElementById('chatgpt-functions').value;

            if (!question) {
                alert('Please enter a question!');
                return;
            }

            $('#chatgpt-answer').html('Loading...');

            const $btn = $(this);
            const originalText = $btn.text();
            $btn.prop('disabled', true).text(originalText + '...');

            // Prepare request data
            let requestData = {
                'question': question,
                'department_id': departmentId,
                'debug_mode': 1
            };

            // Add optional fields if provided
            if (vectorStorageId) {
                requestData.vector_storage_id = vectorStorageId;
            }
            if (systemPrompt) {
                requestData.system_prompt = systemPrompt;
            }

            if (functions) {
                requestData.functions = functions;
            }

            // Add message history if present
            if (messageHistory.length > 0) {
                requestData.message_history = JSON.stringify(messageHistory);
            }

            // Use the returned jqXHR so we can attach always() to re-enable the button
            const jq = $.postJSON(WWW_DIR_JAVASCRIPT + 'chatgpt/getanswer/answer', requestData);

            jq.done(function(data){
                let responseHtml = data.response;
                
                // Check if we should populate message history (checkbox is unchecked)
                const shouldPopulateHistory = !$('#do-not-populate-history').is(':checked');
                
                // If response type is not 'function_call', add visitor and assistant messages to history
                if (data.response_type !== 'function_call' && shouldPopulateHistory) {
                    // Add user's question to history
                    messageHistory.push({
                        role: 'user',
                        content: question
                    });
                    
                    // Add assistant's response to history
                    messageHistory.push({
                        role: 'assistant',
                        content: data.response
                    });
                    
                    // Update the message history display
                    renderMessageHistory();
                } else if (data.response_type === 'function_call') {
                    responseHtml = '<strong>Function Call Response:</strong><br/><pre>' + escapeHtml(data.response) + '</pre>';
                }
                
                if (data.request_log) {
                    responseHtml += '<hr />';
                    responseHtml += '<button type="button" class="btn btn-sm btn-secondary mb-2" id="toggle-debug-btn">';
                    responseHtml += '<i class="material-icons" style="font-size: 14px; vertical-align: middle;">bug_report</i> ';
                    responseHtml += 'Show Debug Output';
                    responseHtml += '</button>';
                    responseHtml += '<div id="debug-output" style="display: none;" class="p-1">';
                    responseHtml += '<textarea class="blockquote blockquote-code mx550 w-100" style="height:500px;">' + escapeHtml(JSON.stringify(data.request_log, null, 2)) + '</textarea>';
                    responseHtml += '</div>';
                }
                
                $('#chatgpt-answer').html(responseHtml);
                
                // Attach click handler for debug toggle button
                $('#toggle-debug-btn').click(function() {
                    const $debugOutput = $('#debug-output');
                    const $btn = $(this);
                    
                    if ($debugOutput.is(':visible')) {
                        $debugOutput.slideUp();
                        $btn.html('<i class="material-icons" style="font-size: 14px; vertical-align: middle;">bug_report</i> Show Debug Output');
                    } else {
                        $debugOutput.slideDown();
                        $btn.html('<i class="material-icons" style="font-size: 14px; vertical-align: middle;">bug_report</i> Hide Debug Output');
                    }
                });
            }).fail(function(){
                alert('request failed');
            }).always(function(){
                $btn.prop('disabled', false).text(originalText);
            });
        });

    })();
</script>