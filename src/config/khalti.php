<?php

return [
    'debug' => true, // set false to run on live khalti url
    'auto_redirect' => true, // set false if you don't want khalti to auto redirect
    'website_url' => 'https://example.com', // your website url
    'live_public_key' => env('KHALTI_LIVE_PUBLIC_KEY', ''), // public key from khalti
    'live_secret_key' => env('KHALTI_LIVE_SECRET_KEY', ''), // secret key from khalti
    'test_public_key' => env('KHALTI_TEST_PUBLIC_KEY', ''), // public key from khalti
    'test_secret_key' => env('KHALTI_TEST_SECRET_KEY', '') // secret key from khalti
];
