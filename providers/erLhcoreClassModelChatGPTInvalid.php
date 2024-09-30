<?php

namespace LiveHelperChatExtension\chatgpt\providers;

class erLhcoreClassModelChatGPTInvalid
{
    use \erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_chatgpt_invalid';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionChatgpt::getSession';

    public static $dbSortOrder = 'DESC';

    public function getState()
    {
        return array(
            'id' => $this->id,
            'aid' => $this->aid,
            'question' => $this->question,
            'answer' => $this->answer,
            'chat_id' => $this->chat_id,
            'user_id' => $this->user_id,
            'ctime' => $this->ctime,
            'reviewed' => $this->reviewed
        );
    }

    public function beforeSave()
    {
        if ($this->ctime == null) {
            $this->ctime = time();
        }
    }

    public $id = null;
    public $aid = null;
    public $question = '';
    public $answer = '';
    public $chat_id = null;
    public $user_id = null;
    public $ctime = null;
    public $reviewed = 0;
}

?>