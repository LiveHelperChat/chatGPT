<?php

$tpl = erLhcoreClassTemplate::getInstance('lhchatgpt/settingssuggest.tpl.php');

$chatgpt_suggest = erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
$data = (array)$chatgpt_suggest->data;

if (isset($_POST['StoreOptions'])) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('chatgpt/settingssuggest');
        exit;
    }

    $definition = array(
        'project_api_key' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'vstorage_id' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'system_instructions' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'model' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        ),
        'limit_last' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'int'
        ),
        'keep_previous' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ),
        'log_request' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ),
        'chat_auto_suggester' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ),
        'chat_reply_tab' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'boolean'
        ),
        'crawl_api_key' => new ezcInputFormDefinitionElement(
            ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'
        )
    );

    $form = new ezcInputForm( INPUT_POST, $definition );
    $Errors = array();

    if ( $form->hasValidData( 'project_api_key' )) {
        $data['project_api_key'] = $form->project_api_key;
    } else {
        $data['project_api_key'] = '';
    }

    if ( $form->hasValidData( 'vstorage_id' )) {
        $data['vstorage_id'] = $form->vstorage_id;
    } else {
        $data['vstorage_id'] = '';
    }

    if ( $form->hasValidData( 'crawl_api_key' )) {
        $data['crawl_api_key'] = $form->crawl_api_key;
    } else {
        $data['crawl_api_key'] = '';
    }

    if ( $form->hasValidData( 'system_instructions' )) {
        $data['system_instructions'] = $form->system_instructions;
    } else {
        $data['system_instructions'] = '';
    }

    if ( $form->hasValidData( 'model' )) {
        $data['model'] = $form->model;
    } else {
        $data['model'] = '';
    }

    if ( $form->hasValidData( 'keep_previous' )) {
        $data['keep_previous'] = 1;
    } else {
        $data['keep_previous'] = 0;
    }

    if ( $form->hasValidData( 'chat_auto_suggester' )) {
        $data['chat_auto_suggester'] = 1;
    } else {
        $data['chat_auto_suggester'] = 0;
    }

    if ( $form->hasValidData( 'chat_reply_tab' )) {
        $data['chat_reply_tab'] = 1;
    } else {
        $data['chat_reply_tab'] = 0;
    }

    if ( $form->hasValidData( 'log_request' )) {
        $data['log_request'] = 1;
    } else {
        $data['log_request'] = 0;
    }

    if ( $form->hasValidData( 'limit_last' )) {
        $data['limit_last'] = $form->limit_last;
    } else {
        $data['limit_last'] = 1;
    }

    $chatgpt_suggest->explain = '';
    $chatgpt_suggest->type = 0;
    $chatgpt_suggest->hidden = 1;
    $chatgpt_suggest->identifier = 'chatgpt_suggest';
    $chatgpt_suggest->value = serialize($data);
    $chatgpt_suggest->saveThis();

    $tpl->set('updated','done');
}

$tpl->set('chatgpt_suggest',$data);

$Result['content'] = $tpl->fetch();

$Result['path'] = array(
    array(
        'url' => erLhcoreClassDesign::baseurl('chatgpt/index'),
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Chat GPT')
    ),
    array(
        'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('chatgpt/module','Settings')
    )
);

?>