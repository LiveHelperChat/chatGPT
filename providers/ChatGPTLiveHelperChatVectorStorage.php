<?php

namespace LiveHelperChatExtension\chatgpt\providers;

class ChatGPTLiveHelperChatVectorStorage {

    /**
     * Send request to OpenAI API
     *
     * @param string $endpoint API endpoint
     * @param string $method HTTP method
     * @param array $data Request data (optional)
     * @return array Response from API
     */
    private static function sendRequest($endpoint, $method = 'GET', $data = null)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/' . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if ($data !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Bearer ' . self::getApiKey(),
            ]);
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . self::getApiKey(),
            ]);
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    /**
     * Create a vector storage
     *
     * @param array $params Parameters for creating vector storage
     * @return array Response from API
     */
    public static function createStorage($params)
    {
        return self::sendRequest('vector_stores', 'POST', $params);
    }

    /**
     * Retrieve vector storage information
     *
     * @param string $storageId ID of the vector storage
     * @return array Response from API
     */
    public static function getStorage($storageId)
    {
        return self::sendRequest('vector_stores/' . $storageId);
    }

    /**
     * Update vector storage
     *
     * @param string $storageId ID of the vector storage
     * @param array $params Parameters to update
     * @return array Response from API
     */
    public static function updateStorage($storageId, $params)
    {
        return self::sendRequest('vector_stores/' . $storageId, 'PATCH', $params);
    }

    /**
     * Delete vector storage
     *
     * @param string $storageId ID of the vector storage
     * @return array Response from API
     */
    public static function deleteStorage($storageId)
    {
        return self::sendRequest('vector_stores/' . $storageId, 'DELETE');
    }

    /**
     * List all vector storages
     *
     * @param array $params Query parameters (e.g. ['limit' => 10, 'after' => 'storage_id'])
     * @return array Response from API
     */
    public static function listStorages($params = [])
    {
        $query = !empty($params) ? '?' . http_build_query($params) : '';
        return self::sendRequest('vector_stores' . $query);
    }

    /**
     * Get API key from system configuration
     *
     * @return string API key
     */
    private static function getApiKey()
    {
        return \erLhcoreClassModelChatConfig::fetch('chatgpt_suggest')->data['project_api_key'];
    }


    /**
     * List vector store files
     *
     * @return array Response from API
     */
    public static function listFiles($storageId, $params = ['limit' => 100])
    {
        $query = !empty($params) ? '?' . http_build_query($params) : '';
        return self::sendRequest('vector_stores/' . $storageId . '/files' . $query);
    }

    /**
     * Get file information
     *
     * @param string $fileId ID of the file
     * @return array Response from API
     */
    public static function getFile($fileId)
    {
        return self::sendRequest('/files/' . $fileId);
    }


    /**
     * Upload a file to OpenAI
     *
     * @param string $filePath Path to the file to upload
     * @param string $purpose Purpose of the file (e.g., 'fine-tune', 'assistants', etc.)
     * @return array Response from API
     */
    public static function uploadFile($filePath, $fileName, $purpose = 'user_data')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.openai.com/v1/files');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $postFields = [
            'purpose' => $purpose,
            'file' => new \CURLFile($filePath, null, $fileName)
        ];

        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . self::getApiKey()
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    /**
     * Attach a file to vector storage
     *
     * @param string $storageId ID of the vector storage
     * @param string $fileId ID of the file to attach
     * @return array Response from API
     */
    public static function attachFileToStorage($storageId, $fileId)
    {
        $data = [
            'file_id' => $fileId
        ];

        return self::sendRequest('vector_stores/' . $storageId . '/files', 'POST', $data);
    }

    /**
     * Delete a file from OpenAI
     *
     * @param string $fileId ID of the file to delete
     * @return array Response from API
     */
    public static function deleteFile($fileId)
    {
        return self::sendRequest('files/' . $fileId, 'DELETE');
    }

    /**
     * Delete a file from vector storage
     *
     * @param string $storageId ID of the vector storage
     * @param string $fileId ID of the file to delete from storage
     * @return array Response from API
     */
    public static function deleteFileFromVectoreStorage($storageId, $fileId)
    {
        return self::sendRequest('vector_stores/' . $storageId . '/files/' . $fileId, 'DELETE');
    }

}

?>