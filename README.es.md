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
  - [Subir medios](#subir-medios)
  - [Mensajes en crudo (raw)](#mensajes-en-crudo-raw)
  - [Consultar plantillas registradas en Meta](#consultar-plantillas-registradas-en-meta)
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
| Obtener metadata de medios | ✅ |
| Consultar plantillas registradas (WhatsAppTemplate) | ✅ |
| Paginación de plantillas | ✅ |
| Modo de prueba / fake | ✅ |
| Envío de documentos | ❌ (TODO) |
| Subir documentos | ❌ (TODO) |
| Subir audio | ❌ (TODO) |
| Subir stickers | ❌ (TODO) |
| Mensajes de audio | ❌ |
| Mensajes de sticker | ❌ |
| Mensajes de ubicación | ❌ |
| Mensajes de contacto | ❌ |
| Mensajes de reacción | ❌ |
| Catálogos / multi-producto | ❌ |
| Mensajes Flow | ❌ |
| Manejo de webhooks | ❌ |
| Perfil de negocio | ❌ |

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

### Subir medios

```php
use Axolotesource\LaravelWhatsappApi\WhatsAppMessages\WhatsAppMedia;

// Subir imagen
$media = WhatsAppMedia::image('/ruta/imagen.jpg')->upload();

// Subir video
$media = WhatsAppMedia::video('/ruta/video.mp4')->upload();

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

## Licencia

MIT
