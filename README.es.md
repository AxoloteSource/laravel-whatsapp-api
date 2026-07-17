# Laravel WhatsApp API

[![Latest Version](https://img.shields.io/packagist/v/axolotesource/laravel-whatsapp-api)](https://packagist.org/packages/axolotesource/laravel-whatsapp-api)
[![Total Downloads](https://img.shields.io/packagist/dt/axolotesource/laravel-whatsapp-api.svg?style=flat-square)](https://packagist.org/packages/axolotesource/laravel-whatsapp-api)
[![License](https://img.shields.io/packagist/l/axolotesource/laravel-whatsapp-api)](LICENSE)

Paquete de Laravel para enviar mensajes de WhatsApp de forma sencilla utilizando la API de WhatsApp Cloud (Graph API).

## Índice

- [Instalación](#instalación)
- [Variables de entorno](#variables-de-entorno-env)
- [Funcionalidades](#funcionalidades)
- [Uso](#uso)
  - [Mensajes de texto](#mensajes-de-texto)
  - [Enviar plantillas (Templates)](#enviar-plantillas-templates)
  - [Botones interactivos](#botones-interactivos)
  - [Listas interactivas](#listas-interactivas)
  - [Enviar imágenes](#enviar-imágenes)
  - [Enviar video por URL](#enviar-video-por-url)
  - [Enviar documentos](#enviar-documentos)
  - [Enviar audio](#enviar-audio)
  - [Enviar stickers](#enviar-stickers)
  - [Enviar ubicación](#enviar-ubicación)
  - [Enviar contactos](#enviar-contactos)
  - [Enviar reacciones](#enviar-reacciones)
  - [Subir medios](#subir-medios)
  - [Mensajes en crudo (raw)](#mensajes-en-crudo-raw)
  - [Consultar plantillas registradas en Meta](#consultar-plantillas-registradas-en-meta)
  - [Consultar información del número de teléfono](#consultar-información-del-número-de-teléfono)
  - [Consultar perfil de negocio](#consultar-perfil-de-negocio)
  - [Modo de prueba](#modo-de-prueba)
  - [Método toArray](#método-toarray)
- [Referencia de API](#referencia-de-api)
- [Licencia](#licencia)

## Instalación

```bash
composer require axolotesource/laravel-whatsapp-api
```

Publica el archivo de configuración:

```bash
php artisan vendor:publish --provider="Axolotesource\LaravelWhatsappApi\LaravelWhatsappApiServiceProvider"
```

### Variables de entorno (`.env`)

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

### Funcionalidades

| Característica | Estado |
|---|---|
| Mensajes de texto | ✅ |
| Plantillas (templates) con componentes | ✅ |
| Botones interactivos (hasta 3) | ✅ |
| Listas interactivas con secciones | ✅ |
| Imágenes (por ID de recurso) | ✅ |
| Imágenes (por URL) | ✅ |
| Videos (por URL) | ✅ |
| Mensajes en crudo (raw) | ✅ |
| Subir imágenes | ✅ |
| Subir videos | ✅ |
| Subir documentos | ✅ |
| Subir audio | ✅ |
| Subir stickers | ✅ |
| Envío de documentos | ✅ |
| Mensajes de audio | ✅ |
| Mensajes de sticker | ✅ |
| Obtener metadata de medios | ✅ |
| Consultar plantillas registradas (WhatsAppTemplate) | ✅ |
| Paginación de plantillas | ✅ |
| Información del número de teléfono (quality rating, messaging limit) | ✅ |
| Modo de prueba / fake | ✅ |
| Mensajes de ubicación | ✅ |
| Mensajes de contacto | ✅ |
| Mensajes de reacción | ✅ |
| Catálogos / multi-producto | ❌ |
| Mensajes Flow | ❌ |
| Manejo de webhooks | ❌ |
| Perfil de negocio | ✅ |

## Uso

### Enviar plantillas (Templates)

El primer mensaje para un nuevo contacto debe ser una plantilla aprobada por Meta.

```php
WhatsAppMessages::templete('521234567890')
    ->language('es_MX')
    ->addComponents([
        BodyComponent::create([
            Params::text('Este es un mensaje de prueba')
        ]),
        ButtonComponent::create([
            Params::button('sí')
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
            Params::button('nunca')
        ])
    ]);
```

Si no pasas el nombre de la plantilla, usará el valor de `default_initial_templete` en la configuración.

#### Componentes de plantilla

- **BodyComponent** – Cuerpo del mensaje
- **HeaderComponent** – Encabezado
- **ButtonComponent** – Botones (soporta `QUICK_REPLY` y `URL`)

#### Parámetros

```php
Params::text(string $text, ?string $parameterName = null)
Params::button(string $payload)
Params::imageFromUrl(string $url)
```

### Mensajes de texto

```php
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMessages;

WhatsAppMessages::text('521234567890')
    ->body('Hola, este es un mensaje de texto')
    ->send();

// También puedes deshabilitar la previsualización de URLs
WhatsAppMessages::text('521234567890', false)
    ->body('Mensaje sin preview de URL')
    ->send();
```

Usando `textMessage` (reemplaza a `text`, que está deprecado):

```php
WhatsAppMessages::textMessage('521234567890')
    ->body('Hola mundo')
    ->send();
```

### Botones interactivos

Hasta 3 botones de respuesta rápida.

```php
WhatsAppMessages::interactiveButtons('521234567890')
    ->setHeaderText('Encabezado opcional')
    ->body('Cuerpo del mensaje')
    ->footer('Pie opcional')
    ->addButton('Opción 1', 1)
    ->addButton('Opción 2', 2)
    ->addButton('Opción 3', 3)
    ->send();
```

También soporta header multimedia:

```php
WhatsAppMessages::interactiveButtons('521234567890')
    ->setHeaderImage('https://ejemplo.com/imagen.jpg')
    ->body('Cuerpo del mensaje')
    ->addButton('Sí', 'yes')
    ->addButton('No', 'no')
    ->send();
```

### Listas interactivas

```php
WhatsAppMessages::interactiveList('521234567890')
    ->body('Elige una opción:')
    ->button('Ver opciones')
    ->addSection('Sección 1', [
        Row::create('Título fila 1', 'id-1', 'Descripción opcional'),
        Row::create('Título fila 2', 'id-2'),
    ])
    ->addSection('Sección 2', [
        Row::create('Título fila 3', 'id-3'),
    ])
    ->send();
```

### Enviar imágenes

Por ID de recurso (requiere subir el archivo primero):

```php
$media = WhatsAppMedia::image('/ruta/local/imagen.jpg')->upload();

WhatsAppMessages::image('521234567890', $media)
    ->send();
```

Por URL:

```php
WhatsAppMessages::imageByUrl('521234567890', 'https://ejemplo.com/imagen.jpg')
    ->send();
```

### Enviar video por URL

```php
WhatsAppMessages::videoByUrl('521234567890', 'https://ejemplo.com/video.mp4')
    ->send();
```

### Enviar documentos

Por ID de recurso (requiere subir el archivo primero):

```php
$media = WhatsAppMedia::document('/ruta/local/factura.pdf')->upload();

WhatsAppMessages::document('521234567890', $media)
    ->caption('Aquí está tu factura')
    ->filename('factura-2024.pdf')
    ->send();
```

Por URL:

```php
WhatsAppMessages::documentByUrl('521234567890', 'https://ejemplo.com/factura.pdf', 'factura.pdf')
    ->send();
```

Tipos de documento soportados: `pdf`, `doc`, `docx`, `xls`, `xlsx`, `ppt`, `pptx`, `txt`.

### Enviar audio

Por ID de recurso:

```php
$media = WhatsAppMedia::audio('/ruta/local/voz.ogg')->upload();

WhatsAppMessages::audio('521234567890', $media)
    ->send();
```

Por URL:

```php
WhatsAppMessages::audioByUrl('521234567890', 'https://ejemplo.com/voz.ogg')
    ->send();
```

Tipos de audio soportados: `aac`, `mp4`, `mpeg`, `amr`, `ogg`.

### Enviar stickers

Por ID de recurso:

```php
$media = WhatsAppMedia::sticker('/ruta/local/sticker.webp')->upload();

WhatsAppMessages::sticker('521234567890', $media)
    ->send();
```

Por URL:

```php
WhatsAppMessages::stickerByUrl('521234567890', 'https://ejemplo.com/sticker.webp')
    ->send();
```

Los stickers deben estar en formato `image/webp`.

### Enviar ubicación

```php
WhatsAppMessages::location('521234567890')
    ->latitude(19.4326)
    ->longitude(-99.1332)
    ->name('CDMX Centro')
    ->address('Plaza de la Constitución, Centro Histórico')
    ->send();
```

Tanto `latitude` como `longitude` son obligatorios. `name` y `address` son opcionales.

### Enviar contactos

Envía uno o varios contactos. Usa los value objects `ContactName`, `ContactPhone` y `Contact` para componer los datos:

```php
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Contact\Contact;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Contact\ContactName;
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\Messages\Contact\ContactPhone;

$contact = Contact::create(ContactName::create('John Doe')->firstName('John')->lastName('Doe'))
    ->addPhone(ContactPhone::create('+521234567890')->type(ContactPhone::TYPE_CELL))
    ->addPhone(ContactPhone::create('+522222222222')->type(ContactPhone::TYPE_WORK))
    ->addEmail('john@example.com', 'WORK')
    ->addUrl('https://example.com', 'WORK')
    ->addOrg('Acme Corp', 'Engineering', 'CTO')
    ->addAddress('123 Main St', 'Mexico City', 'CDMX', '06000', 'Mexico', 'MX', 'WORK')
    ->birthday('1990-05-15');

WhatsAppMessages::contact('521234567890')
    ->addContact($contact)
    ->send();
```

Puedes agregar varios contactos en un solo mensaje:

```php
WhatsAppMessages::contact('521234567890')
    ->addContact(Contact::create(ContactName::create('John Doe')))
    ->addContact(Contact::create(ContactName::create('Jane Smith')))
    ->send();
```

Tipos de teléfono disponibles: `CELL`, `MAIN`, `IPHONE`, `HOME`, `WORK`.

### Enviar reacciones

Reacciona a un mensaje previamente enviado usando su `wamid`. El `message_id` es el id del mensaje al que quieres reaccionar:

```php
WhatsAppMessages::reaction('521234567890')
    ->messageId('wamid.HBgNMTIzNDU2Nzg5MBUCABEYEjQxRjcwNjdFQUE')
    ->emoji('👍')
    ->send();
```

Para eliminar una reacción, envía el mismo `message_id` con un `emoji` vacío:

```php
WhatsAppMessages::reaction('521234567890')
    ->messageId('wamid.HBgNMTIzNDU2Nzg5MBUCABEYEjQxRjcwNjdFQUE')
    ->emoji('')
    ->send();
```

### Consultar perfil de negocio

`WhatsAppBusinessProfile` permite obtener la información del perfil de tu cuenta de WhatsApp Business (about, descripción, email, sitios web, foto de perfil, vertical, dirección):

```php
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppBusinessProfile;

$profile = WhatsAppBusinessProfile::info()->get();

echo $profile->about;              // Descripción corta
echo $profile->description;        // Descripción larga
echo $profile->email;              // Email de contacto
echo $profile->profilePictureUrl;  // URL de la foto de perfil
echo $profile->vertical;           // Vertical de la industria (RETAIL, etc.)
echo $profile->websites[0];        // Primer sitio web
echo $profile->address;            // Dirección del negocio
```

#### BusinessProfileDTO

El perfil de negocio se devuelve como un `BusinessProfileDTO`:

| Propiedad | Tipo | Descripción |
|---|---|---|
| `about` | `string` | Descripción corta del negocio (máx 139 caracteres) |
| `description` | `string` | Descripción larga del negocio (máx 512 caracteres) |
| `email` | `string` | Email de contacto del negocio |
| `profilePictureUrl` | `string` | URL de la foto de perfil |
| `vertical` | `string` | Vertical de la industria |
| `websites` | `array` | Array de URLs de sitios web |
| `address` | `string\|null` | Dirección del negocio |

#### Modo simulado (fake)

```php
WhatsAppMessages::fake();
$profile = WhatsAppBusinessProfile::info()->get();
```

### Subir medios

```php
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMedia;

// Subir imagen
$media = WhatsAppMedia::image('/ruta/imagen.jpg')->upload();

// Subir video
$media = WhatsAppMedia::video('/ruta/video.mp4')->upload();

// Subir documento
$media = WhatsAppMedia::document('/ruta/factura.pdf')->upload();

// Subir audio
$media = WhatsAppMedia::audio('/ruta/voz.ogg')->upload();

// Subir sticker
$media = WhatsAppMedia::sticker('/ruta/sticker.webp')->upload();

// Obtener metadata de un medio por ID
$media = WhatsAppMedia::retrieve('MEDIA_ID')->get();
```

### Mensajes en crudo (raw)

Para requests completamente personalizados:

```php
WhatsAppMessages::raw([
    'type' => 'text',
    'text' => [
        'body' => 'Hola {{nombre}}'
    ],
], '521234567890', ['nombre' => 'Luis'])
    ->send();
```

### Consultar plantillas registradas en Meta

`WhatsAppTemplate` permite obtener información de las plantillas registradas en tu cuenta de WhatsApp Business.

```php
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppTemplate;
```

#### Obtener todas las plantillas

```php
$templates = WhatsAppTemplate::all(); // Collection de TemplateDTO
```

#### Filtros

```php
// Por estado
WhatsAppTemplate::where('status', 'APPROVED')->get();

// Por categoría
WhatsAppTemplate::where('category', 'UTILITY')->get();

// Por nombre
WhatsAppTemplate::where('name', 'mi_plantilla')->get();

// Por idioma
WhatsAppTemplate::where('language', 'es_MX')->get();

// Múltiples valores
WhatsAppTemplate::whereIn('status', ['APPROVED', 'PENDING'])->get();
```

#### Seleccionar campos

```php
WhatsAppTemplate::select(['name', 'status', 'category', 'language', 'components'])->get();
```

Campos disponibles: `id`, `name`, `status`, `category`, `language`, `components`, `last_updated_time`, `quality_score`, `rejected_reason`.

#### Límite de resultados

```php
WhatsAppTemplate::limit(10)->get();
```

#### Paginación

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

Cada plantilla se devuelve como un `TemplateDTO` con las siguientes propiedades:

| Propiedad | Tipo | Descripción |
|---|---|---|
| `id` | `string` | ID de la plantilla |
| `name` | `string` | Nombre de la plantilla |
| `status` | `string` | Estado (`APPROVED`, `PENDING`, `REJECTED`, etc.) |
| `category` | `string` | Categoría (`MARKETING`, `UTILITY`, `AUTHENTICATION`) |
| `language` | `string` | Código de idioma |
| `components` | `array` | Array de `ComponentDTO` |

#### Modo simulado (fake)

```php
WhatsAppMessages::fake();
WhatsAppTemplate::all(); // retorna datos simulados
```

### Consultar información del número de teléfono

`WhatsAppPhoneNumber` permite obtener información de tu número de teléfono de WhatsApp Business, incluyendo quality rating y messaging limit tier.

```php
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppPhoneNumber;
```

#### Obtener información del número de teléfono

```php
$info = WhatsAppPhoneNumber::info()->get();

echo $info->id;                // ID del número de teléfono
echo $info->verifiedName;      // Nombre verificado del negocio
echo $info->qualityRating;     // Quality rating (GREEN, YELLOW, RED)
echo $info->qualityScore;      // Quality score (0-100)
echo $info->messagingLimitTier; // Messaging limit tier (TIER_1000, TIER_10000, etc.)
```

#### PhoneNumberDTO

La información del número de teléfono se devuelve como un `PhoneNumberDTO` con las siguientes propiedades:

| Propiedad | Tipo | Descripción |
|---|---|---|
| `id` | `string` | ID del número de teléfono |
| `verifiedName` | `string` | Nombre verificado del negocio |
| `qualityRating` | `string` | Quality rating (`GREEN`, `YELLOW`, `RED`) |
| `qualityScore` | `int` | Quality score (0-100) |
| `messagingLimitTier` | `string` | Messaging limit tier |

#### Modo simulado (fake)

```php
WhatsAppMessages::fake();
$info = WhatsAppPhoneNumber::info()->get(); // retorna datos simulados
```

### Modo de prueba

```php
// Activar para que todos los mensajes se envíen al número de prueba
WhatsAppMessages::fake();
```

Cuando `WHATSAPP_BUSINESS_TEST_MODE=true`, todos los mensajes se redirigen automáticamente a `WHATSAPP_BUSINESS_TEST_NUMBER`.

### Método `toArray()`

Puedes obtener el payload sin enviarlo:

```php
$payload = WhatsAppMessages::text('521234567890')
    ->body('Hola')
    ->toArray();
```

## Referencia de API

| Método | Descripción |
|---|---|
| `WhatsAppMessages::text($to, $previewUrl)` | Mensaje de texto simple |
| `WhatsAppMessages::textMessage($to, $previewUrl)` | Mensaje de texto (reemplaza a `text`) |
| `WhatsAppMessages::templete($to, $templateName)` | Enviar plantilla aprobada |
| `WhatsAppMessages::interactiveButtons($to)` | Botones interactivos (hasta 3) |
| `WhatsAppMessages::interactiveList($to)` | Lista interactiva con secciones |
| `WhatsAppMessages::image($to, Media $media)` | Imagen por ID de recurso |
| `WhatsAppMessages::imageByUrl($to, $url)` | Imagen por URL |
| `WhatsAppMessages::videoByUrl($to, $url)` | Video por URL |
| `WhatsAppMessages::document($to, Media $media)` | Documento por ID de recurso |
| `WhatsAppMessages::documentByUrl($to, $url, $filename)` | Documento por URL |
| `WhatsAppMessages::audio($to, Media $media)` | Audio por ID de recurso |
| `WhatsAppMessages::audioByUrl($to, $url)` | Audio por URL |
| `WhatsAppMessages::sticker($to, Media $media)` | Sticker por ID de recurso |
| `WhatsAppMessages::stickerByUrl($to, $url)` | Sticker por URL |
| `WhatsAppMessages::location($to)` | Enviar mensaje de ubicación |
| `WhatsAppMessages::contact($to)` | Enviar mensaje de contacto(s) |
| `WhatsAppMessages::reaction($to)` | Enviar reacción a un mensaje |
| `WhatsAppMedia::document($path)` | Subir un documento |
| `WhatsAppMedia::audio($path)` | Subir audio |
| `WhatsAppMedia::sticker($path)` | Subir sticker |
| `WhatsAppMessages::raw($request, $to, $params)` | Payload en crudo con reemplazo de variables |
| `WhatsAppMessages::test($to)` | Envía plantilla "hello_world" de prueba |
| `WhatsAppMessages::fake()` | Activa respuestas simuladas en pruebas |
| `WhatsAppTemplate::all()` | Obtener todas las plantillas registradas |
| `WhatsAppTemplate::get()` | Obtener plantillas con filtros |
| `WhatsAppTemplate::list()` | Obtener plantillas con paginación (`TemplateList`) |
| `WhatsAppTemplate::where($field, $value)` | Filtrar por status, category, name, language |
| `WhatsAppTemplate::whereIn($field, $values)` | Filtrar por múltiples valores |
| `WhatsAppTemplate::select($fields)` | Seleccionar campos específicos |
| `WhatsAppTemplate::limit($n)` | Limitar número de resultados |
| `WhatsAppPhoneNumber::info()` | Obtener instancia de información del número de teléfono |
| `WhatsAppBusinessProfile::info()` | Obtener instancia del perfil de negocio |

## Licencia

MIT
