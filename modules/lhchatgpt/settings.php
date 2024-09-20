<?php

$tpl = erLhcoreClassTemplate::getInstance('lhchatgpt/settings.tpl.php');

if (isset($_POST['CreateUpdateRestAPI'])) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('chatgpt/settings');
        exit;
    }

    \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatActivator::installOrUpdate([
        'CHAT_GPT_TOKEN' => $_POST['project_api_key'],
        'CHATGPT_ASSISTANT_ID' => $_POST['assistant_id']
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
    array(
        'url' => erLhcoreClassDesign::baseurl('zapier/settings'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','ChatGPT settings')
    )
);

?>