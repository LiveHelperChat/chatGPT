<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Integration settings'); ?></h1>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Settings updated'); ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

    <div class="row">
        <div class="col-6">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/settings','Project API Key');?></label>
                <input type="text" class="form-control form-control-sm" name="project_api_key" value="<?php echo htmlspecialchars($chatgpt_suggest['project_api_key'] ?? '')?>" />
            </div>
        </div>
        <div class="col-6">
            <div class="form-group">
                <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/settings','Vector storage ID');?></label>
                <input type="text" class="form-control form-control-sm" name="vstorage_id" value="<?php echo htmlspecialchars($chatgpt_suggest['vstorage_id'] ?? '')?>" />
                <small>
                    <i>
                        <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/settings','Used as default storage for answer suggestions.');?>
                    </i>
                </small>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/settings','Crawl API Key');?></label>
        <input type="text" class="form-control form-control-sm" name="crawl_api_key" value="<?php echo htmlspecialchars($chatgpt_suggest['crawl_api_key'] ?? '')?>" />
        <p>
            <small>
                <i>
                    <?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/settings','API key used for authentication of pending crawl requests. Enter any alphanumeric random string.');?>. <a target="_blank" href="https://github.com/LiveHelperChat/crawler-to-md?tab=readme-ov-file#integration-with-chatgpt-extension"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/settings','Crawler setup instructions.');?></a>
                </i>
            </small>
        </p>
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/settings','System instructions for answer suggestions.');?></label>
        <textarea class="form-control form-control-sm" name="system_instructions"><?php echo htmlspecialchars($chatgpt_suggest['system_instructions'] ?? '')?></textarea>
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Default model')?></label>
        <input type="text" name="model" placeholder="Default - gpt-4o-mini" value="<?php echo htmlspecialchars($chatgpt_suggest['model'] ?? '')?>"  class="form-control form-control-sm" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Send to AI only last n messages'); ?></label>
        <input type="text" name="limit_last" placeholder="5" class="form-control form-control-sm" value="<?php echo htmlspecialchars($chatgpt_suggest['limit_last'] ??  '')?>" />
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