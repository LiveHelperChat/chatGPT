<?php

/**
 * Direct integration with Chat GPT
 * */
#[\AllowDynamicProperties]
class erLhcoreClassExtensionChatgpt
{
    public function __construct()
    {

    }

    public function run()
    {
        $dispatcher = erLhcoreClassChatEventDispatcher::getInstance();

        if ($this->settings['suggester'] === true) {
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

            $dispatcher->listen('instance.extensions_structure', array(
                $this,
                'checkStructure'
            ));

            $dispatcher->listen('instance.registered.created', array(
                $this,
                'instanceCreated'
            ));

            $dispatcher->listen('instance.destroyed', array(
                $this,
                'instanceDestroyed'
            ));
        }
    }

    public function instanceDestroyed($params)
    {
        // Set subdomain manual, so we avoid calling in cronjob
        $this->instanceManual = $params['instance'];
    }

    /**
     * Used only in automated hosting enviroment
     */
    public function instanceCreated($params)
    {
        try {
            // Instance created trigger
            $this->instanceManual = $params['instance'];

            // Just do table updates
            erLhcoreClassUpdate::doTablesUpdate(json_decode(file_get_contents('extension/chatgpt/doc/structure.json'), true));
        } catch (Exception $e) {
            erLhcoreClassLog::write(print_r($e, true));
        }
    }

    public function checkStructure()
    {
        erLhcoreClassUpdate::doTablesUpdate(json_decode(file_get_contents('extension/chatgpt/doc/structure.json'), true));
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

    public function __get($var) {
        switch ($var) {
            case 'settings' :
                $this->settings = file_exists('extension/chatgpt/settings/settings.ini.php') ? include ('extension/chatgpt/settings/settings.ini.php') : ['crawler' => true, 'suggester' => true];
                return $this->settings;
            default :
                ;
                break;
        }
    }

    private static $persistentSession;

    private $instanceManual = false;
}