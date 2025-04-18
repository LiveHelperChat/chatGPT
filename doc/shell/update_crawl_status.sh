#!/bin/bash

# Configuration
API_KEY="your_api_key"
API_URL="https://example.com/chatgptvector/updatecrawlstatus"
CRAWL_ID=2
STATUS=3  # Completed status
NUMBER_OF_PAGES=42
MD_FILE="./sample.md"  # Replace with the path to your MD file

# Check if file exists
if [ ! -f "$MD_FILE" ]; then
    echo "Error: File $MD_FILE does not exist!"
    exit 1
fi

# Get the filename without the path
FILE_NAME=$(basename "$MD_FILE")

# Encode the file content to base64
FILE_CONTENT=$(base64 -w 0 "$MD_FILE")

#  "file_name": "$FILE_NAME",

# Create JSON payload
JSON_PAYLOAD=$(cat <<EOF
{
  "crawl_id": $CRAWL_ID,
  "status": $STATUS,
  "number_of_pages": $NUMBER_OF_PAGES,
  "file_content": "$FILE_CONTENT"
}
EOF
)

# Send the request
curl -X POST \
  -H "Content-Type: application/json" \
  -H "X-API-KEY: $API_KEY" \
  -d "$JSON_PAYLOAD" \
  "$API_URL"

echo -e "\n\nRequest sent!"