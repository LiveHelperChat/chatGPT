<h4><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','ChatGPT AI');?></h4>

<ul>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('chatgpt/settings')?>"><span class="material-icons">settings</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','ChatGPT Bot integration settings');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('chatgpt/settingssuggest')?>"><span class="material-icons">quiz</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','ChatGPT Setting for answers suggesting');?></a></li>
    <li><a href="<?php echo erLhcoreClassDesign::baseurl('chatgpt/test')?>"><span class="material-icons">labs</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Test UI');?></a></li>
    <?php if (erLhcoreClassUser::instance()->hasAccessTo('lhchatgpt','manage_invalid')) : ?>
        <li><a href="<?php echo erLhcoreClassDesign::baseurl('chatgpt/invalid')?>"><span class="material-icons">report</span><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Reported invalid suggestions');?></a></li>
    <?php endif;?>
</ul>