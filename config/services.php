<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'google-analytics'=>[
        
        'service_account' => storage_path('app/google/local-bebop-450021-v2-640a0e13f44b.json'),
        'view_id' => '476634976', // Google Analytics VIEW ID
        
    ],//store extern service configuration 
    'google_analytics' => [
    'property_id' => env('GOOGLE_ANALYTICS_PROPERTY_ID'),
    'credentials_path' => env('GOOGLE_ANALYTICS_CREDENTIALS', 'storage/app/analytics/service-account-credentials.json'),
],


];
