<?php if (erLhcoreClassUser::instance()->hasAccessTo('lhchatgpt','access_index')) : ?>
    <li class="nav-item"><a class="nav-link" href="<?php echo erLhcoreClassDesign::baseurl('chatgpt/index')?>"><i class="material-icons">integration_instructions</i><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','OpenAI');?></a></li>
<?php endif; ?>
