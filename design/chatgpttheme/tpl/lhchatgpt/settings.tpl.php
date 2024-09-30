<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','ChatGPT Integration as a bot settings'); ?></h1>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Settings updated'); ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Project API Key')?></label>
        <input type="text" name="project_api_key" value="<?php isset($_POST['project_api_key']) ? print htmlspecialchars($_POST['project_api_key']) : ''?>"  class="form-control form-control-sm" />
    </div>

    <div class="form-group">
        <label><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Assistant ID')?></label>
        <input type="text" name="assistant_id" value="<?php isset($_POST['assistant_id']) ? print htmlspecialchars($_POST['assistant_id']) : ''?>"  class="form-control form-control-sm" />
    </div>

    <div class="btn-group" role="group" aria-label="Basic example">

        <button name="CreateUpdateRestAPI" class="btn btn-sm btn-info"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Create/Update Rest API/Bot'); ?></button>

        <?php if (\erLhcoreClassModelGenericBotRestAPI::getCount(['filter' => ['name' => 'ChatGPTAssistant']]) == 1) : ?>
            <button name="RemoveRestAPI" class="btn btn-sm btn-danger"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Remove Rest API/Bot call'); ?></button>
        <?php endif; ?>
    </div>

</form>