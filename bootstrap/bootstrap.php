<?php

/**
 * Direct integration with Chat GPT
 * */
class erLhcoreClassExtensionChatgpt
{
    public function __construct()
    {

    }

    public function run()
    {
        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();
        
        /**
         * User events
         */
        $dispatcher->listen('chat.syncadmin', array(
            $this,
            'syncAdmin'
        ));

        $dispatcher->listen('chat.loadinitialdata', array(
            $this,
            'loadInitialData'
        ));

        $dispatcher->listen('chat.delete', array(
            $this,
            'chatDelete'
        ));
    }

    public static function getSession()
    {
        if (!isset (self::$persistentSession)) {
            self::$persistentSession = new ezcPersistentSession (ezcDbInstance::get(), new ezcPersistentCodeManager ('./extension/chatgpt/pos'));
        }
        return self::$persistentSession;
    }

    public function loadInitialData($params) {
        $sjOptions = \erLhcoreClassModelChatConfig::fetch('chatgpt_suggest');
        $data = (array)$sjOptions->data;
        $params['lists']['lhcchatgpt'] = array('enabled' => erLhcoreClassUser::instance()->hasAccessTo('lhchatgpt','auto_suggester') && isset($data['chat_auto_suggester']) && $data['chat_auto_suggester'] == '1');
    }

    public function chatDelete($params) {
        $db = ezcDbInstance::get();
        $stmt = $db->prepare('DELETE FROM lhc_chatgpt_chat WHERE chat_id = :chat_id');
        $stmt->bindValue(':chat_id', $params['chat']->id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function syncAdmin($params) 
    {
        $msgSearch = array();        
        foreach ($params['messages'] as $msg) {
            if ($msg['user_id'] == 0) {
                $msgSearch[] = (int)$msg['id'];
            }
        }

        if (!empty($msgSearch)) {
            $params['response']['chatgptbotids'] = $msgSearch;
        }
    }

    private static $persistentSession;
}