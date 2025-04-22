# Sample of CURL Commands

Presently supported statuses

```php
public const STATUS_IDLE = 0;
public const STATUS_CRAWLING = 1;
public const STATUS_ERROR = 2;
public const STATUS_COMPLETED = 3;
public const STATUS_ERROR_CRAWLING = 4;
```

## Fetch pending crawls

Fetches crawls that need to be processed. Note that `url` is now returned as an array of URLs.

```shell
curl -X GET \
  "${API_BASE_URL}/fetchpendingcrawls" \
  -H "X-API-Key: your_api_key" \
  -H "Content-Type: application/json"
```

Example response:
```json
{
  "error": false,
  "result": [
    {
      "id": 123,
      "name": "Example Crawl",
      "vector_storage_id": 456,
      "url": ["https://example.com/page1", "https://example.com/page2"],
      "base_url": "https://example.com",
      "start_url": "https://example.com/start",
      "max_pages": 500
    }
  ]
}
```

## Update indexing status without file

Updates the status of a crawl operation:

```shell
curl -X POST \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: your_api_key" \
  -d '{
    "crawl_id": 123,
    "status": 1,
    "number_of_pages": 42
  }' \
  "${API_BASE_URL}/updatecrawlstatus"
```

## Update status with completed status and file

There are two ways to upload the completed file:

```shell
curl -X POST \
  -H "X-API-KEY: your_api_key" \
  -F "crawl_id=123" \
  -F "status=3" \
  -F "number_of_pages=42" \
  -F "file=@/path/to/your/file.md" \
  "${API_BASE_URL}/updatecrawlstatus"
```