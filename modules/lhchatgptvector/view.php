<?php

$tpl = erLhcoreClassTemplate::getInstance('lhchatgptvector/view.tpl.php');

$item = null;

if (isset($Params['user_parameters']['id'])) {
    try {
        $storage = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::getStorage($Params['user_parameters']['id']);
        $files = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::listFiles($Params['user_parameters']['id']);

        $tpl->set('storage', $storage);
        $tpl->set('files', $files);
        $tpl->set('storage_id', $Params['user_parameters']['id']);
    } catch (Exception $e) {
        $tpl->set('errors', [$e->getMessage()]);
    }
}

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array (
        'url' =>erLhcoreClassDesign::baseurl('chatgpt/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module','OpenAI')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('chatgptvector/list'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Vector storages')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'View vector storage')
    )
);