### INSTALL


``composer require axolotesource/laravel-whatsapp-api``

To publish the configuration file, run the following command:

```bash
php artisan vendor:publish
```
When prompted to select the provider, choose Axolotesource\LaravelWhatsappApi\LaravelWhatsappApiServiceProvider.

### [WhatsAppMessages](src%2FWhatsAppMessages%2FWhatsAppMessages.php)

#### Examples

##### templete(string $to, string $templateName = null): Template

This method allows you to send a template-type message (this is the first message that should be sent to the client). For the second parameter, it can be null, and if so, it retrieves the value from config('laravel-whatsapp-api.default_initial_template');.

```php
$whatsappMessage = WhatsAppMessages::templete($phoneNumber)
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
        ButtonComponent::create([
            Params::button('never')
        ])
    ]);
```

##### interactiveButtons(string $to): InteractiveButtons

```php
WhatsAppMessages::interactiveButtons($phoneNumber)
    ->setHeaderImage(
        'https://url.com/test.jpg'
    )
    ->addButton('Button 1', 1)
    ->addButton('Button 2', 2)
    ->addButton('Button 3', 3)
    ->body('This is te body message')
    ->send();
```


## Coming song more documentation....
