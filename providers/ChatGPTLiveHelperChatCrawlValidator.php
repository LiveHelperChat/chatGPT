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

        // Validate URL format
        if (isset($item->url) && !empty($item->url) && !filter_var($item->url, FILTER_VALIDATE_URL)) {
            $errors[] = \erTranslationClassLhTranslation::getInstance()->getTranslation('lhchatgptcrawl/validator', 'Please enter a valid URL');
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
}