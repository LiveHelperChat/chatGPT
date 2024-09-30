<?php

$tpl = erLhcoreClassTemplate::getInstance('lhchatgpt/index.tpl.php');

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','ChatGPT')
    )
);

?>