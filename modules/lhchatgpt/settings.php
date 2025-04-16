<?php

$tpl = erLhcoreClassTemplate::getInstance('lhchatgpt/settings.tpl.php');

if (isset($_POST['CreateUpdateRestAPI'])) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('chatgpt/settings');
        exit;
    }

    \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatActivator::installOrUpdate([
        'CHAT_GPT_TOKEN' => $_POST['project_api_key'],
        'CHAT_GPT_STORAGE_ID' => $_POST['vstorage_id']
    ]);

    $tpl->set('updated','done');
}

if (isset($_POST['RemoveRestAPI'])) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('chatgpt/settings');
        exit;
    }

    \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatActivator::remove();
    $tpl->set('updated','done');
}

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array (
        'url' =>erLhcoreClassDesign::baseurl('chatgpt/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','ChatGPT')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','ChatGPT settings')
    )
);

?>