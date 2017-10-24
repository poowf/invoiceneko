<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, SparkPost and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\Models\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'facebook' => [
        'client_id' => env('SOCIALITE_FACEBOOK_CLIENTID'),
        'client_secret' => env('SOCIALITE_FACEBOOK_SECRET'),
        'redirect' => env('SOCIALITE_FACEBOOK_REDIRECT'),
    ],

    'google' => [
        'client_id' => env('SOCIALITE_GOOGLE_CLIENTID'),
        'client_secret' => env('SOCIALITE_GOOGLE_SECRET'),
        'redirect' => env('SOCIALITE_GOOGLE_REDIRECT'),
    ],

];
