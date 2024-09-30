<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Integration settings'); ?></h1>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Settings updated'); ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Project API Key')?></label>
        <input type="text" name="project_api_key" value="<?php isset($chatgpt_suggest['project_api_key']) ? print htmlspecialchars($chatgpt_suggest['project_api_key']) : ''?>"  class="form-control form-control-sm" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Assistant ID')?></label>
        <input type="text" name="assistant_id" value="<?php isset($chatgpt_suggest['assistant_id']) ? print htmlspecialchars($chatgpt_suggest['assistant_id']) : ''?>"  class="form-control form-control-sm" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Send to AI only last n messages'); ?></label>
        <input type="text" name="limit_last" placeholder="5" class="form-control form-control-sm" value="<?php isset($chatgpt_suggest['limit_last']) ? print htmlspecialchars($chatgpt_suggest['limit_last']) : ''?>" />
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="keep_previous" value="1" <?php if (isset($chatgpt_suggest['keep_previous']) && $chatgpt_suggest['keep_previous'] == 1) : ?>checked="checked"<?php endif;?> /> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','On next visitor message keep previous suggestions'); ?></label>
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="log_request" value="1" <?php if (isset($chatgpt_suggest['log_request']) && $chatgpt_suggest['log_request'] == 1) : ?>checked="checked"<?php endif;?> /> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Log request made to AI'); ?></label>
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="chat_auto_suggester" value="1" <?php if (isset($chatgpt_suggest['chat_auto_suggester']) && $chatgpt_suggest['chat_auto_suggester'] == 1) : ?>checked="checked"<?php endif;?> /> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Automatically suggest answers based on visitor messages'); ?></label>
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="chat_reply_tab" value="1" <?php if (isset($chatgpt_suggest['chat_reply_tab']) && $chatgpt_suggest['chat_reply_tab'] == 1) : ?>checked="checked"<?php endif;?> /> <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Enable reply prediction tab in chat UI'); ?></label>
    </div>

    <button name="StoreOptions" class="btn btn-sm btn-secondary" type="submit"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Save'); ?></button>

</form>