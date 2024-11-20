# ChatGPT assistant workflow model support

With this extension you won't need to pay anything to third party except OpenAI for contextual search based on your personal data.

### Present release

* Now you can use streaming workflow. For streaming to work you have to have those extensions up and running. 
* NodeJS (https://github.com/LiveHelperChat/NodeJS-Helper)
* PHP-Resque (https://github.com/LiveHelperChat/lhc-php-resque)
* Most recent versions of Live Helper Chat and NodeJS is required
  * https://github.com/LiveHelperChat/livehelperchat/commit/dfcb0597179d961eef1b922093db5f7679475511
  * https://github.com/LiveHelperChat/NodeJS-Helper/commit/582a273acb1222e7bde6f50e2bfd58079c74b85d
 * Bot and Rest API json files you will find here https://github.com/LiveHelperChat/chatGPT/tree/main/doc/assistant_stream 
   * Import first Rest API after that import bot and choose just imported Rest API 
   * Remember to change where you see `{CHAT_GPT_TOKEN}` and `{CHATGPT_ASSISTANT_ID}`

### v1.2

* Added support for empty first visitor message on chat start. Rest API call now uses `{not_emtpy_*` feature.
* Meta data for a Run is stored within chat, because there can be no message during run. E.g first run on chat start
    * Because of this chat `Bot` and `Rest API` calls were modified.

Required version since this - https://github.com/LiveHelperChat/livehelperchat/commit/5b2f0b25404dfe8bf56a15d5031086de3622c496

### v1.1

Updates

* Multiple tools call support. `ScheduleRun` Rest API call will use `"parallel_tool_calls": false,` argument
* Bot now has example how to have multiple tools calls with arguments

Make sure you have most recent Rest API and Bot versions also version with this commit https://github.com/LiveHelperChat/livehelperchat/commit/6e9ecaa573902adc082204799fec98f7796d81d9

### V1.0

Initial release

## Requirements

* Min 4.51v Live Helper Chat version.
* At the moment Polling workflow is supported only.
* Account at https://platform.openai.com/docs/overview

## How to make UI snappy by delegating Rest API calls to background workers?

* Install https://github.com/LiveHelperChat/lhc-php-resque
* Modify Bot triggers and where you see `Rest APi` call response check `Send Rest API Call in the background if we are not already in it`.

## Install

After clone or download put it under

`extension/chatgpt` - don't forget to lowercase a folder

Activate extension in main settings file `lhc_web/settings/settings.ini.php` extension section chatgpt by Adding lines:

```
'extensions' =>  array (  'chatgpt'  ),
```

## Install database

Execute queries from 

`doc/install.sql`

Or execute command

```shell
php cron.php -s site_admin -e chatgpt -c cron/update_structure
```

## Back office

Navigate to back office and click clear cache

Under left Modules you will find `ChatGPT` click it. 

### Setup as a bot

 * Click `ChatGPT Bot integration settings`
 * Now just paste `Project API Key` and `Assistant ID` from https://platform.openai.com and click `Create/Update Rest API/Bot`

Now you can just assign newly created bot to your department or modify bot to the way you want.

#### How to avoid bot calling functions if visitor is not logged in?

In a `ScheduleRun` Rest API call you can have something like `{is_empty__args.chat.chat_variables_array.is_logged}` which checks that chat variable is set.

```
{
    "assistant_id": "asst_UAJYImd9WyXlRGNOa0bcTAgD",
     "parallel_tool_calls": false
     {is_empty__args.chat.chat_variables_array.is_logged}
        ,"tools":[]
        , "additional_instructions":"Visitor is not logged in and functions calls are not enable to him. You can answer questions only from documentation. Ask him to login to get personal account information. You can get personal information once visitor is logged in."
     {/is_empty}
     {not_empty__msg_url},"additional_messages" : [{"role" : "user", "content" :  {{msg_url}} }]{/not_empty}
}
```

### Setup pas reply predictor for the agents

 * Click `ChatGPT Setting for answers suggesting` 
 * `Project API Key` and `Assistant ID` can be different than Bot
 * Fer reply predictions to work you have to activate bot from `Setup as a bot` step. We use some of the Rest API calls.
 
### How to have only manual reply predictions tab in the chat interface?

Have only `Enable reply prediction tab in chat UI` checked.

### How to have only manual reply predictions tab in the chat interface?

Have `Automatically suggest answers based on visitor messages`

## Setup video tutorial

Video tutorial and use case - https://youtu.be/X9W99obVj8Q

# ChatGPT Completion workflow model support

Please refer to this manual https://doc.livehelperchat.com/docs/bot/chatgpt-integration 

# Screenshots

## Multiple tool calls

### Update phone number.

Phone number can be evening and morning as an example.

Chat GPT function definition
```json
{
  "name": "post-update-phone-number",
  "description": "Updates the phone number for a player.",
  "strict": true,
  "parameters": {
    "type": "object",
    "required": [
      "phone",
      "type"
    ],
    "properties": {
      "type": {
        "type": "string",
        "description": "The type of phone number (e.g., 'morning','evening').",
        "enum": [
          "evening_phone",
          "morning_phone"
        ]
      },
      "phone": {
        "type": "string",
        "description": "The new phone number to be updated."
      }
    },
    "additionalProperties": false
  }
}
```

Live Helper Chat

![image](https://github.com/user-attachments/assets/8839d268-f15b-4101-bb95-7460dd8a8c13)

### Retrieve withdrawal list for the visitor

Chat GPT function definition

```json
{
  "name": "get_withdrawals",
  "description": "Retrieves a list of withdrawals for a visitor.",
  "strict": true,
  "parameters": {
    "type": "object",
    "required": [],
    "properties": {},
    "additionalProperties": false
  }
}
```

Live Helper Chat side. You can also as visitor can ask to return only last 3 items.

![image](https://github.com/user-attachments/assets/c109ca7e-14cc-456e-be65-80c2e18bdef3)


