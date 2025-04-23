<?php

namespace LiveHelperChatExtension\chatgpt\providers;

class ChatGPTLiveHelperChatCrawlValidator {

    /**
     * Validates a crawl configuration
     *
     * @param \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl $crawl
     * @return array Array of errors if validation fails, empty array otherwise
     */
    public static function validateCrawl(\LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl & $item)
    {
        $definition = array(
            'name' => new \ezcInputFormDefinitionElement(\ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'),
            'url' => new \ezcInputFormDefinitionElement(\ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'),
            'start_url' => new \ezcInputFormDefinitionElement(\ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'),
            'base_url' => new \ezcInputFormDefinitionElement(\ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'),
            'max_pages' => new \ezcInputFormDefinitionElement(\ezcInputFormDefinitionElement::OPTIONAL, 'int', ['min_range' => 0]),
            'crawl_frequency' => new \ezcInputFormDefinitionElement(\ezcInputFormDefinitionElement::OPTIONAL, 'unsafe_raw'),
        );

        $form = new \ezcInputForm(INPUT_POST, $definition);
        $errors = array();

        if ($form->hasValidData('name') && $form->name != '') {
            $item->name = $form->name;
        } else {
            $errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Please enter a name for the crawler');
        }

        if ($form->hasValidData('max_pages')) {
            $item->max_pages = $form->max_pages;
        } else {
            $item->max_pages = 0;
        }

        // Validate start_url
        if ($form->hasValidData('start_url') && $form->start_url != '') {
            if (filter_var($form->start_url, FILTER_VALIDATE_URL)) {
                $item->start_url = $form->start_url;
            } else {
                $errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/validator', 'Please enter a valid start URL');
            }
        } else {
            $item->start_url = '';
        }

        // Validate base_url
        if ($form->hasValidData('base_url') && $form->base_url != '') {
            if (filter_var($form->base_url, FILTER_VALIDATE_URL)) {
                $item->base_url = $form->base_url;
            } else {
                $errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/validator', 'Please enter a valid base URL');
            }
        } else {
            $errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/validator', 'Please enter a valid base URL');
        }

        if ($form->hasValidData('url') && $form->url != '') {
            $urls = preg_split('/\r\n|\r|\n/', $form->url);
            $invalidUrls = [];
            $validUrls = [];

            foreach ($urls as $url) {
                $url = trim($url);
                if (!empty($url)) {
                    if (filter_var($url, FILTER_VALIDATE_URL)) {
                        $validUrls[] = $url;
                    } else {
                        $invalidUrls[] = $url;
                    }
                }
            }

            if (!empty($invalidUrls)) {
                $errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/validator', 'Some URLs are invalid: ') . implode(', ', $invalidUrls);
            }

            if (!empty($validUrls)) {
                $item->url = implode("\n", $validUrls);
            } else {
                $errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/new', 'Please enter at least one valid URL to crawl');
            }
        } else {
            $item->url = '';
        }

        if ($form->hasValidData('crawl_frequency')) {
            $item->crawl_frequency = $form->crawl_frequency;
        }

        // Validate crawl frequency
        if (isset($item->crawl_frequency) && (!is_numeric($item->crawl_frequency) || $item->crawl_frequency <= 0)) {
            $errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/validator', 'Crawl frequency should be a positive number representing hours');
        }

        return $errors;
    }
    /**
     * Updates the crawl status
     *
     * @param \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl $crawl
     * @param string|null $fileContent
     * @param string|null $fileName
     * @return bool
     */    
    public static function updateCrawlStatus(\LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl $crawl, $fileContent = null, $fileName = null)
    {
        $db = \ezcDbInstance::get();
        $db->beginTransaction();
        
        try {
            // If Crawl Object file_id is not empty, delete the existing file
            if (!empty($crawl->file_id)) {
                // Get the vector storage ID from the crawl object
                $vector_storage_id = $crawl->vector_storage_id;
                
                // Delete the file from OpenAI Vector Storage
                $responseDeleteFromStorage = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteFileFromVectorStorage($vector_storage_id, $crawl->file_id);
                
                // Delete the file itself
                $responseDeleteFile = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteFile($crawl->file_id);
                
                // Clear the file_id since we've deleted the old file
                $crawl->file_id = '';
            }

            // If there's an existing LHC file, we should remove it
            if (!empty($crawl->lhc_file_id)) {
                try {
                    // Fetch the file by ID
                    $fileData = \erLhcoreClassModelChatFile::fetch($crawl->lhc_file_id);
                    if ($fileData instanceof \erLhcoreClassModelChatFile) {
                        // Remove the file from the system
                        $fileData->removeThis();
                    }
                } catch (\Exception $e) {
                    // Log error but continue with the process
                    \erLhcoreClassLog::write('Error removing LHC file: ' . $e->getMessage());
                }
                
                // Clear the lhc_file_id since we've deleted the old file
                $crawl->lhc_file_id = null;
            }
            
            // If new file content and name are provided, upload the new file
            if ($fileContent !== null && $fileName !== null) {
                // Upload the file to OpenAI files API
                $responseFile = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::uploadFile($fileContent, $fileName, 'user_data', true);
                
                if (isset($responseFile['id'])) {
                    // Attach the file to the vector storage
                    $response = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::attachFileToStorage($crawl->vector_storage_id, $responseFile['id']);
                    
                    if (isset($response['error']['message'])) {
                        // If error attaching to storage, delete the uploaded file to avoid orphaned files
                        $responseDeleteFile = \LiveHelperChatExtension\chatgpt\providers\ChatGPTLiveHelperChatVectorStorage::deleteFile($responseFile['id']);
                        throw new \Exception('Error attaching file to vector storage: ' . $response['error']['message']);
                    } else {
                        // Update the crawl with the new file_id
                        $crawl->file_id = $responseFile['id'];
                        $crawl->last_crawled_at = time();
                        $crawl->status = \LiveHelperChatExtension\chatgpt\providers\erLhcoreClassModelChatGPTCrawl::STATUS_COMPLETED;

                        // Also upload to LHC file system
                        $lhcFileId = self::uploadFileToLHC($fileContent, $fileName, $crawl);
                        if ($lhcFileId > 0) {
                            $crawl->lhc_file_id = $lhcFileId;
                        }
                    }
                } else {
                    throw new \Exception('Failed to upload file: ' . (isset($responseFile['error']) ? json_encode($responseFile['error']) : 'Unknown error'));
                }
            }
            
            // Save the updated crawl object
            $crawl->saveThis();
            $db->commit();
            
            return true;
            
        } catch (\Exception $e) {

            print_r($e);

            $db->rollback();
            // Log error
            \erLhcoreClassLog::write(print_r($e->getMessage(), true));
            return false;
        }
    }

    /**
     * Uploads a file to the LHC file system
     * 
     * @param string $fileContent The content of the file
     * @param string $fileName The name of the file
     * @param string $crawlName The name of the crawl (for reference)
     * @return int The ID of the uploaded file, or 0 if failed
     */
    public static function uploadFileToLHC($fileContent, $fileName, $crawl)
    {
        try {
            // Create a temporary file
            $tempFilePath = 'var/tmpfiles/' . uniqid() . '-' . md5($fileName);
            if (file_put_contents($tempFilePath, $fileContent) === false) {
                return 0;
            }

            // Create a new chat file object
            $chatFile = new \erLhcoreClassModelChatFile();
            $chatFile->size = filesize($tempFilePath);
            $chatFile->type = mime_content_type($tempFilePath);
            $chatFile->name = md5($fileName);
            $chatFile->date = time();
            $chatFile->user_id = 0; // System upload
            $chatFile->upload_name = "[Crawl: " . $crawl->name . "] " . $fileName;
            $chatFile->extension = strtolower(end(explode('.',$fileName)));
            $chatFile->persistent = 1;

            // Generate a file path based on LHC conventions
            $dir = 'var/storage/'.date('Y').'y/'.date('m').'/'.date('d').'/carwl-'. $crawl->id . '/';
            
            // Make sure the directory exists
            \erLhcoreClassFileUpload::mkdirRecursive($dir);
            
            // Generate a file name
            $chatFile->file_path = $dir;
            
            // Move the file to its final destination
            if (rename($tempFilePath, $chatFile->file_path . md5($fileName))) {
                // Change file permission
                chmod($chatFile->file_path . md5($fileName), 0644);
                
                // Save the file object
                $chatFile->saveThis();
                
                return $chatFile->id;
            }
            
            // Clean up if the move failed
            if (file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
            
            return 0;
            
        } catch (\Exception $e) {
            \erLhcoreClassLog::write('Error uploading file to LHC: ' . $e->getMessage());
            
            // Clean up any temporary file
            if (isset($tempFilePath) && file_exists($tempFilePath)) {
                unlink($tempFilePath);
            }
            
            return 0;
        }
    }
}