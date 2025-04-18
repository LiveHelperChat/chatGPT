<?php

$tpl = erLhcoreClassTemplate::getInstance('lhchatgpt/test.tpl.php');

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array (
        'url' =>erLhcoreClassDesign::baseurl('chatgpt/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','OpenAI')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','Test')
    )
);

?>