<?php

return [
    'debug' => true, // set false to run on live khalti url
    'auto_redirect' => true, // set false if you don't want khalti to auto redirect
    'website_url' => 'https://example.com', // your website url
    'live_public_key' => env('KHALTI_PUBLIC_KEY', ''), // public key from khalti
    'live_secret_key' => env('KHALTI_SECRET_KEY', '') // secret key from khalti
];
