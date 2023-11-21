<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ACCOUNT ID
    |--------------------------------------------------------------------------
    |
    | DESCRIPTION
    |
    */

    'account_id' => env('WHATSAPP_BUSINESS_ACCOUNT_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | PHONE NUMBER ID
    |--------------------------------------------------------------------------
    |
    | DESCRIPTION
    |
    */
    'phone_number_id' => env('WHATSAPP_BUSINESS_PHONE_NUMBER_ID', ''),

    /*
    |--------------------------------------------------------------------------
    | Token Bearer
    |--------------------------------------------------------------------------
    |
    | Your app access token (Bearer)
    |
    */

    'bearer' => env('WHATSAPP_BUSINESS_BEARER', ''),

    /*
    |--------------------------------------------------------------------------
    | Verify token
    |--------------------------------------------------------------------------
    |
    | Token to return when hook receive a get petition more info
    | https://developers.facebook.com/docs/graph-api/webhooks/getting-started
    |
    */

    'verify_token' => env('WHATSAPP_HOOK_VERIFY_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Base url
    |--------------------------------------------------------------------------
    |
    | The base url to facebook graph api
    |
    */

    'api' => env('WHATSAPP_BUSINESS_API', 'https://graph.facebook.com/v15.0/'),

    /*
    |--------------------------------------------------------------------------
    | Test number
    |--------------------------------------------------------------------------
    |
    | Test number is the number to send messages when config 'tes_number'
    | is true
    |
    */

    'test_number' => env('WHATSAPP_BUSINESS_TEST_NUMBER', ''),

    /*
    |--------------------------------------------------------------------------
    | Test mode
    |--------------------------------------------------------------------------
    |
    | If test mode is true, all messages send to 'test_number'
    |
    */

    'test_mode' => env('WHATSAPP_BUSINESS_TEST_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Templetes
    |--------------------------------------------------------------------------
    |
    | Default initial templete
    |
    */

    'default_initial_templete' => env('DEFAULT_INITIAL_TEMPLETE', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Raw json to send templete
    |--------------------------------------------------------------------------
    |
    |
    |
    */
    'raw_templete' => env('WHATSAPP_RAW_TEMPLETE'),

    /*
    |--------------------------------------------------------------------------
    | Templete imagen head
    |--------------------------------------------------------------------------
    |
    | Image to show in the head of the templete
    |
    */
    'templete_imagen_head' => env('TEMPLETE_IMAGEN_HEAD'),

    /*
    |--------------------------------------------------------------------------
    | Validations
    |--------------------------------------------------------------------------
    |
    |
    |
    */

    'validations' => [
        'comment_max' => 60,
        'row_title_max' => 20
    ],

    /*
    |--------------------------------------------------------------------------
    | Disable hook
    |--------------------------------------------------------------------------
    |
    | Disable hook
    |
    */
    'disable_hook' => env('DISABLE_WHATSAPP_HOOK', false),

    /*
    |--------------------------------------------------------------------------
    | Replicate hook
    |--------------------------------------------------------------------------
    |
    | Image to show in the head of the templete
    |
    */
    'replicate_hook_urls' => json_decode(env('REPLICATE_WHATSAPP_HOOK_URLS', '[]')),
];
