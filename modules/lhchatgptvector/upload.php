<?php

$tpl = erLhcoreClassTemplate::getInstance( 'lhchatgptvector/upload.tpl.php');
$tpl->set('file_uploaded', false);

if (isset($_POST['UploadFileAction'])) {

    if (!isset($_POST['csfr_token']) || !$currentUser->validateCSFRToken($_POST['csfr_token'])) {
        erLhcoreClassModule::redirect('file/list');
        exit;
    }

    if (empty($errors)) {
        $fileData = erLhcoreClassModelChatConfig::fetch('file_configuration');
        $data = (array)$fileData->data;

        try {

            if (erLhcoreClassSearchHandler::isFile('files',explode('|',$data['ft_op']))) {

                $dir = 'var/tmpfiles/';

                erLhcoreClassFileUpload::mkdirRecursive($dir);

                $fileNameAray = explode('.', $_FILES['files']['name']);
                end($fileNameAray);
                $extension = strtolower(current($fileNameAray));

                $filename = erLhcoreClassSearchHandler::moveUploadedFile('files', $dir, '.');

                $responseFile = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::uploadFile($dir . $filename, $_FILES['files']['name']);

                unlink($dir . $filename);

                if (isset($responseFile['id'])) {

                    $response = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::attachFileToStorage($Params['user_parameters']['id'], $responseFile['id']);

                    if (isset($response['error']['message'])) {
                        $responseDeleteFile = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteFile($responseFile['id']);
                        throw new Exception($response['error']['message']);
                    } else {
                        $tpl->set('file_uploaded', true);
                    }

                } else {
                    throw new Exception('Upload file failed!' . json_encode($responseFile));
                }

            } else {
                $tpl->set('errors', array('Invalid file type.'));
            }

        } catch (Exception $e) {
            $tpl->set('errors', array($e->getMessage()));
        }
    } else {
        $tpl->set('errors', $errors);
    }
}

$Result['content'] = $tpl->fetch();

$Result['pagelayout'] = 'popup';

$Result['path'] = array(
    array('url' => erLhcoreClassDesign::baseurl('system/configuration'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','System configuration')),
    array('url' => erLhcoreClassDesign::baseurl('file/list'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','List of files')),
    array('url' => erLhcoreClassDesign::baseurl('file/new'),'title' => erTranslationClassLhTranslation::getInstance()->getTranslation('system/configuration','New file')));

?>