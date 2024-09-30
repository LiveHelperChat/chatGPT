<?php

namespace LiveHelperChatExtension\chatgpt\providers;

class erLhcoreClassModelChatGPTChat
{
    use \erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_chatgpt_chat';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionChatgpt::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        return array(
            'id' => $this->id,
            'thread_id' => $this->thread_id,
            'chat_id' => $this->chat_id
        );
    }

    public $id = null;
    public $thread_id = null;
    public $chat_id = null;
}

?>