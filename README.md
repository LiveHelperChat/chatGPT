# ChatGPT responses workflow model support

This extension was updated to `Responses` API https://doc.livehelperchat.com/docs/bot/chatgpt-responses

With this extension you won't need to pay anything to third party except OpenAI for contextual search based on your personal data. Streaming is also supported!

For those setups samples you don't need to setup extension

Differences between those API - https://platform.openai.com/docs/guides/responses-vs-chat-completions It is recommended just to use `Responses` API

* Responses API sample setup - https://doc.livehelperchat.com/docs/bot/chatgpt-responses
* Completions API sample setup - https://doc.livehelperchat.com/docs/bot/chatgpt-integration
* Assistant API is depreciated, but you can look at this sample - https://youtu.be/SEsOjmoKdrI

## Notes

* After creating vector storage, you might need to refresh a few times until vector storage appears. I don't control that.  
* After the file is uploaded to vector storage, it's not used immediately show even open AI tells it was uploaded. You might need refresh a window.
* If LCH does not allow to upload file, you might need to adjust chat configuration and allow to upload specific file type.
* OpenAI does not start using files even they are uploaded and completed. By my experience, it can take about 10 minutes before they are started to be used in OpenAI calls.

## Requirements

* Min 4.52v Live Helper Chat version.
* Polling/Streaming workflow is supported only.
* Account at https://platform.openai.com/docs/overview
* `System configuration -> Live Help Configuration -> Chat configuration -> Misc` Make sure you unheck `Reopen chat functionality enabled` and `Allow user to reopen closed chats?`. This is required because if chat is reopened we can be in the middle of previous task.
* `gpt-4o-mini` Model is used.
* The Account is funded with 5$. Sometimes there were reports that some users were getting `404` during API calls.

### Demo

* You can see ChatGPT chat running on https://doc.livehelperchat.com/ it can answer questions only related to Live Helper Chat documentation. Demo also supports streaming.
* For facebook demo look at https://www.facebook.com/LiveHelperChat/ and write message to messenger :)

We are using for this demo https://livehelperchat.com/order/now if you want easily setup your own bot.

* For streaming demo look at https://youtu.be/9clxMjnrGsM
* For setup from scratch without extension, look at https://youtu.be/SEsOjmoKdrI
* For setup on facebook messenger look at https://youtu.be/nIExwuWeb3E

How it works?

* Account at https://platform.openai.com is created
* Background worker is used https://github.com/LiveHelperChat/lhc-php-resque
* To vector store files from https://github.com/LiveHelperChat/doc were uploaded

### How to generate content for your own knowledge base?

Demo bot is using documentation generated from this command. Take a look at official documentation of the tool at https://github.com/obeone/crawler-to-md It generated md file which later was uploaded at https://platform.openai.com

```
docker run --rm -v $(pwd)/output:/app/output -v $(pwd)/cache:/app/cache remdex/crawler-to-md --url https://doc.livehelperchat.com --exclude "/docs/hooks"
```

I have created fork also which fixes few things like absolute links and code tags parsing.

Or just run. Modify to your needs. `--exclude` part is optional

```
docker run --rm -v $(pwd)/output:/app/output -v $(pwd)/cache:/app/cache remdex/crawler-to-md --url https://doc.livehelperchat.com --exclude "/docs/hooks"
```

Build from source
```
git clone https://github.com/LiveHelperChat/crawler-to-md.git && cd crawler-to-md
DOCKER_BUILDKIT=1 docker build -t crawler-to-md .
docker run --rm -v $(pwd)/output:/app/output -v $(pwd)/cache:/app/cache crawler-to-md --url https://doc.livehelperchat.com --exclude "/docs/hooks"
```

#### How to call a trigger based on defined function in ChatGPT?

1. Notice defined function name E.g `transfer_operator`
2. Add [event](bot/triggers.md) to your trigger with `Type` of `Custom text matching` where `Should include any of these words` value should be `transfer_operator`. Screenshot can be found [here](https://doc.livehelperchat.com/docs/bot/chatgpt-integration#how-to-call-a-trigger-based-on-defined-function-in-chatgpt)

### Present release

* Now you can use streaming workflow. For streaming to work you have to have those extensions up and running. 
* NodeJS (https://github.com/LiveHelperChat/NodeJS-Helper)
* PHP-Resque (https://github.com/LiveHelperChat/lhc-php-resque)
* Before sending Rest API call in your bot make sure. Last message is a `Send typing` message. We use typing message indicator and fill its content with streaming content.
* Most recent versions of Live Helper Chat and NodeJS is required
  * https://github.com/LiveHelperChat/livehelperchat/commit/dfcb0597179d961eef1b922093db5f7679475511
  * https://github.com/LiveHelperChat/NodeJS-Helper/commit/582a273acb1222e7bde6f50e2bfd58079c74b85d
 * Bot and Rest API json files you will find here https://github.com/LiveHelperChat/chatGPT/tree/main/doc/assistant_stream 
   * Import first Rest API after that import bot and choose just imported Rest API 
   * Remember to change where you see `{CHAT_GPT_TOKEN}` and `{CHATGPT_ASSISTANT_ID}`

Documentation section regarding how streaming works

https://doc.livehelperchat.com/docs/bot/rest-api#streaming

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
 * Now just paste `Project API Key` and `Vector storage ID` from https://platform.openai.com and click `Create/Update Rest API/Bot`

Now you can just assign newly created bot to your department or modify bot to the way you want.

### Setup pas reply predictor for the agents

 * Click `ChatGPT Setting for answers suggesting` 
 * `Project API Key` and `Vector storage ID` can be different than Bot
 * Fer reply predictions to work you have to activate bot from `Setup as a bot` step. We use some of the Rest API calls.
 
### How to have only manual reply predictions tab in the chat interface?

Have only `Enable reply prediction tab in chat UI` checked.

### How to have automatic answer suggesting in chat window?

Have `Automatically suggest answers based on visitor messages`

![image](https://github.com/LiveHelperChat/chatGPT/blob/main/doc/responses.png?raw=true)

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
  "type": "function",
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

Accessing function call arguments in the bot triggers.

```
{args.chat.chat_variables_array.chatgpt_action_args.phone}
{args.chat.chat_variables_array.chatgpt_action_args.type}
```

Accessing function call arguments in the Rest API

```
{
    "phone":  {{args.chat.chat_variables_array.chatgpt_action_args.phone}},
    "type":{{args.chat.chat_variables_array.chatgpt_action_args.type}}
}
```

Live Helper Chat

![image](https://github.com/user-attachments/assets/8839d268-f15b-4101-bb95-7460dd8a8c13)

### Retrieve withdrawal list for the visitor

Chat GPT function definition

```json
{
  "name": "get_withdrawals",
  "type" : "function",
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


