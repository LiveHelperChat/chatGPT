# ChatGPT assistant workflow model support

With this extension you won't need to pay anything to third party except OpenAI for contextual search based on your personal data.

## Requirements

* Min 4.48v Live Helper Chat version.
* At the moment Polling workflow is supported only.
* Account at https://platform.openai.com/docs/overview

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

Video tutorial under way.

# ChatGPT Completion workflow model support

Please refer to this manual https://doc.livehelperchat.com/docs/bot/chatgpt-integration 