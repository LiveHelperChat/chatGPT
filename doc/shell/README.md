# Sample of CURL Commands

Presently supported statuses

```php
public const STATUS_IDLE = 0;
public const STATUS_CRAWLING = 1;
public const STATUS_ERROR = 2;
public const STATUS_COMPLETED = 3;
```

Fetch which crawls we need to execute

```shell
curl -X GET \
  "https://example.com/chatgptvector/fetchpendingcrawls" \
  -H "X-API-Key: your_api_key" \
  -H "Content-Type: application/json"
```

Update indexing status without file:

```
curl -X POST \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: your_api_key" \
  -d '{
    "crawl_id": 2,
    "status": 1,
    "number_of_pages": 42
  }' \
  "https://example./chatgptvector/updatecrawlstatus"
```

 Update status with completed status and file:

```
curl -X POST \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: your_api_key_here" \
  -d '{
    "crawl_id": 123,
    "status": 3,
    "number_of_pages": 42,
    "file_name": "crawl_results.md",  # Optional, if not provided, crawl name + .md will be used
    "file_content": "BASE64_ENCODED_FILE_CONTENT_HERE"
  }' \
 https://example.com/chatgptvector/updatecrawlstatus
```

