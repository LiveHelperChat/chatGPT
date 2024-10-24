# ChatGPT assistant workflow model support

With this extension you won't need to pay anything to third party except OpenAI for contextual search based on your personal data.


### Present release

* Added support for empty first visitor message on chat start. Rest API call now uses `{not_emtpy_*` feature.
* Meta data for a Run is stored within chat, because there can be no message during run. E.g first run on chat start

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