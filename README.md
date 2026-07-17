# Laravel WhatsApp API

[![Latest Version](https://img.shields.io/packagist/v/axolotesource/laravel-whatsapp-api)](https://packagist.org/packages/axolotesource/laravel-whatsapp-api)
[![Total Downloads](https://img.shields.io/packagist/dt/axolotesource/laravel-whatsapp-api.svg?style=flat-square)](https://packagist.org/packages/axolotesource/laravel-whatsapp-api)
[![License](https://img.shields.io/packagist/l/axolotesource/laravel-whatsapp-api)](LICENSE)

Laravel package to easily send WhatsApp messages using the WhatsApp Cloud API (Graph API).

## Table of Contents

- [Installation](#installation)
- [Environment variables](#environment-variables-env)
- [Features](#features)
- [Usage](#usage)
  - [Text messages](#text-messages)
  - [Sending templates](#sending-templates)
  - [Interactive buttons](#interactive-buttons)
  - [Interactive lists](#interactive-lists)
  - [Sending images](#sending-images)
  - [Sending video by URL](#sending-video-by-url)
  - [Sending documents](#sending-documents)
  - [Sending audio](#sending-audio)
  - [Sending stickers](#sending-stickers)
  - [Uploading media](#uploading-media)
  - [Raw messages](#raw-messages)
  - [Querying registered templates on Meta](#querying-registered-templates-on-meta)
  - [Querying phone number info](#querying-phone-number-info)
  - [Test mode](#test-mode)
  - [toArray method](#toarray-method)
- [API Reference](#api-reference)
- [License](#license)

## Installation

```bash
composer require axolotesource/laravel-whatsapp-api
```

Publish the configuration file:

```bash
php artisan vendor:publish --provider="Axolotesource\LaravelWhatsappApi\LaravelWhatsappApiServiceProvider"
```

### Environment variables (`.env`)

```env
WHATSAPP_BUSINESS_ACCOUNT_ID=
WHATSAPP_BUSINESS_PHONE_NUMBER_ID=
WHATSAPP_BUSINESS_BEARER=
WHATSAPP_HOOK_VERIFY_TOKEN=
WHATSAPP_BUSINESS_API=https://graph.facebook.com/v23.0/
WHATSAPP_BUSINESS_TEST_NUMBER=
WHATSAPP_BUSINESS_TEST_MODE=false
DEFAULT_INITIAL_TEMPLETE=default
WHATSAPP_RAW_TEMPLETE=
TEMPLETE_IMAGEN_HEAD=
DISABLE_WHATSAPP_HOOK=false
REPLICATE_WHATSAPP_HOOK_URLS=[]
```

### Features

| Feature | Status |
|---|---|
| Text messages | ✅ |
| Templates with components | ✅ |
| Interactive buttons (up to 3) | ✅ |
| Interactive lists with sections | ✅ |
| Images (by media ID) | ✅ |
| Images (by URL) | ✅ |
| Videos (by URL) | ✅ |
| Raw messages | ✅ |
| Image upload | ✅ |
| Video upload | ✅ |
| Document upload | ✅ |
| Audio upload | ✅ |
| Sticker upload | ✅ |
| Document sending | ✅ |
| Audio messages | ✅ |
| Sticker messages | ✅ |
| Retrieve media metadata | ✅ |
| Query registered templates (WhatsAppTemplate) | ✅ |
| Template pagination | ✅ |
| Phone number info (quality rating, messaging limit) | ✅ |
| Test / fake mode | ✅ |
| Location messages | ❌ |
| Contact messages | ❌ |
| Reaction messages | ❌ |
| Catalogs / multi-product | ❌ |
| Flow messages | ❌ |
| Webhook handling | ❌ |
| Business profile | ❌ |

## Usage

### Sending templates

The first message to a new contact must be a Meta-approved template.

```php
WhatsAppMessages::templete('521234567890')
    ->language('es_MX')
    ->addComponents([
        BodyComponent::create([
            Params::text('This is a test message')
        ]),
        ButtonComponent::create([
            Params::button('yes')
        ]),
        ButtonComponent::create([
            Params::button('no')
        ]),
        ButtonComponent::create(
            [
                Params::button('history/login?folio=123ABC')
            ],
            ButtonComponent::SUB_TYPE_URL
        ),
        ButtonComponent::create([
            Params::button('never')
        ])
    ]);
```

If you don't pass a template name, it will use the `default_initial_templete` config value.

#### Template components

- **BodyComponent** – Message body
- **HeaderComponent** – Header
- **ButtonComponent** – Buttons (supports `QUICK_REPLY` and `URL`)

#### Parameters

```php
Params::text(string $text, ?string $parameterName = null)
Params::button(string $payload)
Params::imageFromUrl(string $url)
```

### Text messages

```php
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;

WhatsAppMessages::text('521234567890')
    ->body('Hello, this is a text message')
    ->send();

// You can also disable URL previews
WhatsAppMessages::text('521234567890', false)
    ->body('Message without URL preview')
    ->send();
```

Using `textMessage` (replaces `text`, which is deprecated):

```php
WhatsAppMessages::textMessage('521234567890')
    ->body('Hello world')
    ->send();
```

### Interactive buttons

Up to 3 quick reply buttons.

```php
WhatsAppMessages::interactiveButtons('521234567890')
    ->setHeaderText('Optional header')
    ->body('Message body')
    ->footer('Optional footer')
    ->addButton('Option 1', 1)
    ->addButton('Option 2', 2)
    ->addButton('Option 3', 3)
    ->send();
```

Also supports media headers:

```php
WhatsAppMessages::interactiveButtons('521234567890')
    ->setHeaderImage('https://example.com/image.jpg')
    ->body('Message body')
    ->addButton('Yes', 'yes')
    ->addButton('No', 'no')
    ->send();
```

### Interactive lists

```php
WhatsAppMessages::interactiveList('521234567890')
    ->body('Choose an option:')
    ->button('View options')
    ->addSection('Section 1', [
        Row::create('Row title 1', 'id-1', 'Optional description'),
        Row::create('Row title 2', 'id-2'),
    ])
    ->addSection('Section 2', [
        Row::create('Row title 3', 'id-3'),
    ])
    ->send();
```

### Sending images

By media ID (requires uploading the file first):

```php
$media = WhatsAppMedia::image('/local/path/image.jpg')->upload();

WhatsAppMessages::image('521234567890', $media)
    ->send();
```

By URL:

```php
WhatsAppMessages::imageByUrl('521234567890', 'https://example.com/image.jpg')
    ->send();
```

### Sending video by URL

```php
WhatsAppMessages::videoByUrl('521234567890', 'https://example.com/video.mp4')
    ->send();
```

### Sending documents

By media ID (requires uploading the file first):

```php
$media = WhatsAppMedia::document('/local/path/invoice.pdf')->upload();

WhatsAppMessages::document('521234567890', $media)
    ->caption('Here is your invoice')
    ->filename('invoice-2024.pdf')
    ->send();
```

By URL:

```php
WhatsAppMessages::documentByUrl('521234567890', 'https://example.com/invoice.pdf', 'invoice.pdf')
    ->send();
```

Supported document types: `pdf`, `doc`, `docx`, `xls`, `xlsx`, `ppt`, `pptx`, `txt`.

### Sending audio

By media ID:

```php
$media = WhatsAppMedia::audio('/local/path/voice.ogg')->upload();

WhatsAppMessages::audio('521234567890', $media)
    ->send();
```

By URL:

```php
WhatsAppMessages::audioByUrl('521234567890', 'https://example.com/voice.ogg')
    ->send();
```

Supported audio types: `aac`, `mp4`, `mpeg`, `amr`, `ogg`.

### Sending stickers

By media ID:

```php
$media = WhatsAppMedia::sticker('/local/path/sticker.webp')->upload();

WhatsAppMessages::sticker('521234567890', $media)
    ->send();
```

By URL:

```php
WhatsAppMessages::stickerByUrl('521234567890', 'https://example.com/sticker.webp')
    ->send();
```

Stickers must be in `image/webp` format.

### Uploading media

```php
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMedia;

// Upload image
$media = WhatsAppMedia::image('/path/image.jpg')->upload();

// Upload video
$media = WhatsAppMedia::video('/path/video.mp4')->upload();

// Upload document
$media = WhatsAppMedia::document('/path/invoice.pdf')->upload();

// Upload audio
$media = WhatsAppMedia::audio('/path/voice.ogg')->upload();

// Upload sticker
$media = WhatsAppMedia::sticker('/path/sticker.webp')->upload();

// Retrieve media metadata by ID
$media = WhatsAppMedia::retrieve('MEDIA_ID')->get();
```

### Raw messages

For fully custom requests:

```php
WhatsAppMessages::raw([
    'type' => 'text',
    'text' => [
        'body' => 'Hello {{name}}'
    ],
], '521234567890', ['name' => 'John'])
    ->send();
```

### Querying registered templates on Meta

`WhatsAppTemplate` allows you to retrieve information about templates registered in your WhatsApp Business account.

```php
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppTemplate;
```

#### Get all templates

```php
$templates = WhatsAppTemplate::all(); // Collection of TemplateDTO
```

#### Filters

```php
// By status
WhatsAppTemplate::where('status', 'APPROVED')->get();

// By category
WhatsAppTemplate::where('category', 'UTILITY')->get();

// By name
WhatsAppTemplate::where('name', 'my_template')->get();

// By language
WhatsAppTemplate::where('language', 'es_MX')->get();

// Multiple values
WhatsAppTemplate::whereIn('status', ['APPROVED', 'PENDING'])->get();
```

#### Selecting fields

```php
WhatsAppTemplate::select(['name', 'status', 'category', 'language', 'components'])->get();
```

Available fields: `id`, `name`, `status`, `category`, `language`, `components`, `last_updated_time`, `quality_score`, `rejected_reason`.

#### Limit results

```php
WhatsAppTemplate::limit(10)->get();
```

#### Pagination

```php
$list = WhatsAppTemplate::list(); // TemplateList

foreach ($list->data() as $template) {
    echo $template->name . ' - ' . $template->status;
}

if ($list->hasNextPage()) {
    $nextPage = $list->nextPage();
}
```

#### TemplateDTO

Each template is returned as a `TemplateDTO` with the following properties:

| Property | Type | Description |
|---|---|---|
| `id` | `string` | Template ID |
| `name` | `string` | Template name |
| `status` | `string` | Status (`APPROVED`, `PENDING`, `REJECTED`, etc.) |
| `category` | `string` | Category (`MARKETING`, `UTILITY`, `AUTHENTICATION`) |
| `language` | `string` | Language code |
| `components` | `array` | Array of `ComponentDTO` |

#### Fake mode

```php
WhatsAppMessages::fake();
WhatsAppTemplate::all(); // returns simulated data
```

### Querying phone number info

`WhatsAppPhoneNumber` allows you to retrieve information about your WhatsApp Business phone number, including quality rating and messaging limit tier.

```php
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppPhoneNumber;
```

#### Get phone number info

```php
$info = WhatsAppPhoneNumber::info()->get();

echo $info->id;                // Phone number ID
echo $info->verifiedName;      // Verified business name
echo $info->qualityRating;     // Quality rating (GREEN, YELLOW, RED)
echo $info->qualityScore;      // Quality score (0-100)
echo $info->messagingLimitTier; // Messaging limit tier (TIER_1000, TIER_10000, etc.)
```

#### PhoneNumberDTO

The phone number info is returned as a `PhoneNumberDTO` with the following properties:

| Property | Type | Description |
|---|---|---|
| `id` | `string` | Phone number ID |
| `verifiedName` | `string` | Verified business name |
| `qualityRating` | `string` | Quality rating (`GREEN`, `YELLOW`, `RED`) |
| `qualityScore` | `int` | Quality score (0-100) |
| `messagingLimitTier` | `string` | Messaging limit tier |

#### Fake mode

```php
WhatsAppMessages::fake();
$info = WhatsAppPhoneNumber::info()->get(); // returns simulated data
```

### Test mode

```php
// Activate to send all messages to the test number
WhatsAppMessages::fake();
```

When `WHATSAPP_BUSINESS_TEST_MODE=true`, all messages are automatically redirected to `WHATSAPP_BUSINESS_TEST_NUMBER`.

### `toArray()` method

You can get the payload without sending it:

```php
$payload = WhatsAppMessages::text('521234567890')
    ->body('Hello')
    ->toArray();
```

## API Reference

| Method | Description |
|---|---|
| `WhatsAppMessages::text($to, $previewUrl)` | Simple text message |
| `WhatsAppMessages::textMessage($to, $previewUrl)` | Text message (replaces `text`) |
| `WhatsAppMessages::templete($to, $templateName)` | Send approved template |
| `WhatsAppMessages::interactiveButtons($to)` | Interactive buttons (up to 3) |
| `WhatsAppMessages::interactiveList($to)` | Interactive list with sections |
| `WhatsAppMessages::image($to, Media $media)` | Image by media ID |
| `WhatsAppMessages::imageByUrl($to, $url)` | Image by URL |
| `WhatsAppMessages::videoByUrl($to, $url)` | Video by URL |
| `WhatsAppMessages::document($to, Media $media)` | Document by media ID |
| `WhatsAppMessages::documentByUrl($to, $url, $filename)` | Document by URL |
| `WhatsAppMessages::audio($to, Media $media)` | Audio by media ID |
| `WhatsAppMessages::audioByUrl($to, $url)` | Audio by URL |
| `WhatsAppMessages::sticker($to, Media $media)` | Sticker by media ID |
| `WhatsAppMessages::stickerByUrl($to, $url)` | Sticker by URL |
| `WhatsAppMedia::document($path)` | Upload a document |
| `WhatsAppMedia::audio($path)` | Upload audio |
| `WhatsAppMedia::sticker($path)` | Upload sticker |
| `WhatsAppMessages::raw($request, $to, $params)` | Raw payload with variable replacement |
| `WhatsAppMessages::test($to)` | Send "hello_world" test template |
| `WhatsAppMessages::fake()` | Enable fake responses for testing |
| `WhatsAppTemplate::all()` | Get all registered templates |
| `WhatsAppTemplate::get()` | Get templates with filters |
| `WhatsAppTemplate::list()` | Get templates with pagination (`TemplateList`) |
| `WhatsAppTemplate::where($field, $value)` | Filter by status, category, name, language |
| `WhatsAppTemplate::whereIn($field, $values)` | Filter by multiple values |
| `WhatsAppTemplate::select($fields)` | Select specific fields |
| `WhatsAppTemplate::limit($n)` | Limit number of results |
| `WhatsAppPhoneNumber::info()` | Get phone number info instance |

## License

MIT
