<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhchatgpt','configure')) : ?>
    <li class="nav-item"><a class="nav-link" href="<?php echo erLhcoreClassDesign::baseurl('chatgpt/settings')?>"><i class="material-icons">integration_instructions</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','ChatGPT');?></a></li>
<?php endif; ?>
