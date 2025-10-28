<?php if (erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionChatgpt')->settings['suggester'] === true && erLhcoreClassModelChatConfig::fetch('chatgpt_suggest')->data['chat_reply_tab'] == 1) : ?>
<div role="tabpanel" class="tab-pane" id="chatgpt-<?php echo $chat->id?>">
    <form method="post" action="" method="post" onsubmit="return lhcChatGPT.replyByChat(<?php echo $chat->id?>)">

        <div class="form-group">
            <label>Enter your question:</label>
            <textarea id="chatgpt_question_<?php echo $chat->id?>" class="form-control form-control-sm"></textarea>
        </div>

        <button type="submit" class="btn btn-xs btn-info">Send</button>

        <div class="text-muted py-2" id="chatgpt_response_<?php echo $chat->id?>">Response will appear here...</div>

    </form>
</div>
<?php endif;?>