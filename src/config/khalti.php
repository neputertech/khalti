<?php

return [
    'debug' => true, // set false to run on live khalti url
    'website_url' => 'https://example.com', // your website url
    'public_key' => env('KHALTI_PUBLIC_KEY', ''), // public key from khalti
    'secret_key' => env('KHALTI_SECRET_KEY', ''), // secret key from khalti
];
