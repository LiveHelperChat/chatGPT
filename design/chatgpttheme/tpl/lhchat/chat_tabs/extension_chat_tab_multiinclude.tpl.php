<?php if (erLhcoreClassModelChatConfig::fetch('chatgpt_suggest')->data['chat_reply_tab'] == 1) : ?>
<li role="presentation" class="nav-item">
    <a class="nav-link " href="#chatgpt-<?php echo $chat->id?>" aria-controls="chatgpt-<?php echo $chat->id?>" role="tab" data-bs-toggle="tab" title="ChatGPT">
        <span><i class="material-icons me-0">prompt_suggestion</i></span>
    </a>
</li>
<?php endif; ?>