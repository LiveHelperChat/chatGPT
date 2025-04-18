<?php

$tpl = erLhcoreClassTemplate::getInstance('lhchatgptvector/new.tpl.php');

$item = new stdClass();
$item->name = '';

if (isset($_POST['Create'])) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('chatgptvector/new');
        exit;
    }

    $definition = array(
        'name' => new ezcInputFormDefinitionElement(ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'),
    );

    $form = new ezcInputForm(INPUT_POST, $definition);
    $Errors = array();

    if ($form->hasValidData('name') && $form->name != '') {
        $item->name = $form->name;
    } else {
        $Errors[] = erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptvector/new', 'Please enter a name for the vector storage');
    }

    if (empty($Errors)) {

        $params = array(
            'name' => $item->name
        );

        $response = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::createStorage($params);

        if (isset($response['id'])) {
            erLhcoreClassModule::redirect('chatgptvector/list');
            exit;
        } else {
            $tpl->set('errors', array(erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptvector/new', 'Failed to create vector storage. Please try again.')));
        }
    } else {
        $tpl->set('errors', $Errors);
    }
}

$tpl->set('item', $item);

$Result['content'] = $tpl->fetch();
$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('chatgpt/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'OpenAI')
    ),
    array(
        'url' => erLhcoreClassDesign::baseurl('chatgptvector/list'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'Vector storages')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('lhcchatbot/module', 'New vector storage')
    )
);

?>