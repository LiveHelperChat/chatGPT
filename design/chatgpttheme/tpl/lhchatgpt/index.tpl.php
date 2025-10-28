<h4><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','OpenAI');?></h4>

<ul class="lhc-module">
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('chatgpt/settings')?>"><span class="material-icons">settings</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','OpenAI Bot quick setup');?></a></li>

    <li><a href="<?php echo erLhcoreClassDesign::baseurl('chatgpt/settingssuggest')?>"><span class="material-icons">quiz</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','OpenAI Setting');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('chatgpt/test')?>"><span class="material-icons">labs</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Test UI');?></a></li>

    <?php if (erLhcoreClassModule::getExtensionInstance('erLhcoreClassExtensionChatgpt')->settings['suggester'] === true) : ?>
        <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhchatgpt','manage_invalid')) : ?>
            <li><a href="<?php echo erLhcoreClassDesign::baseurl('chatgpt/invalid')?>"><span class="material-icons">report</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Reported invalid suggestions');?></a></li>
        <?php endif;?>
    <?php endif;?>

    <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhchatgptvector','configure')) : ?>
        <li><a href="<?php echo erLhcoreClassDesign::baseurl('chatgptvector/list')?>"><span class="material-icons">report</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Vector storages');?></a></li>
    <?php endif;?>
</ul>