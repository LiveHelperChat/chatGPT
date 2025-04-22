<?php

namespace LiveHelperChatExtension\chatgpt\providers;

class erLhcoreClassModelChatGPTCrawl
{
    public const STATUS_IDLE = 0;
    public const STATUS_CRAWLING = 1;
    public const STATUS_ERROR = 2;
    public const STATUS_COMPLETED = 3;
    
    use \erLhcoreClassDBTrait;

    public static $dbTable = 'lhc_chatgpt_crawl';

    public static $dbTableId = 'id';

    public static $dbSessionHandler = 'erLhcoreClassExtensionChatgpt::getSession';

    public static $dbSortOrder = 'DESC';

    /**
     * Get crawls that are pending to be crawled based on their crawl frequency
     *
     * @return array Array of crawl objects
     */
    public static function getPendingCrawls()
    {
        $currentTime = time();
        
        $q = \ezcDbInstance::get()->createSelectQuery();
        $q->select('*')
            ->from(self::$dbTable)
            ->where(
                $q->expr->lOr(
                    $q->expr->eq('status', self::STATUS_IDLE),
                    $q->expr->eq('status', self::STATUS_COMPLETED)
                ),
                $q->expr->lOr(
                    $q->expr->eq('last_crawled_at', 0),
                    $q->expr->lAnd(
                        $q->expr->gt('crawl_frequency', 0),
                        $q->expr->lt(
                            'last_crawled_at', 
                            ($currentTime . ' - ' . $q->expr->mul('crawl_frequency', 3600))
                        )
                    )
                )
            );
            
        $stmt = $q->prepare();
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        $items = array();
        foreach ($rows as $row) {
            $item = new self();
            $item->setState($row);
            $items[] = $item;
        }
        
        return $items;
    }

    public function getState()
    {
        return array(
            'id' => $this->id,
            'name' => $this->name,
            'vector_storage_id' => $this->vector_storage_id,
            'url' => $this->url,
            'number_of_pages' => $this->number_of_pages,
            'file_id' => $this->file_id,
            'crawl_frequency' => $this->crawl_frequency,
            'last_crawled_at' => $this->last_crawled_at,
            'status' => $this->status,
            'base_url' => $this->base_url,
            'start_url' => $this->start_url,
            'max_pages' => $this->max_pages,
        );
    }

    public $id = null;
    public $name = '';
    public $vector_storage_id = '';
    public $url = '';
    public $number_of_pages = 0;
    public $file_id = '';
    public $crawl_frequency = '';
    public $last_crawled_at = 0;
    public $base_url = '';
    public $start_url = '';
    public $max_pages = 0;
    public $status = self::STATUS_IDLE; // 0 - idle, 1 - crawling, 2 - error
}

?>