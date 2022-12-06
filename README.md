# Laravel Khalti 

This package can help you integrate Khalti new ePayment Gateway (NEW) to your php application.

Here is an example of how you can initiate Khalti transaction:

```php
...
use Neputer\Facades\Khalti;

class PaymentController extends Controller {
    ...
    public function pay() {
        $return_url = "http://example.com/verify";
        $purchase_order_id = "your_transaction_id"; // example 123567;
        $purchase_order_name = "your_order_name"; // example Transaction: 1234,
        $amount = 1000; // Your total amount in paisa Rs 1 = 100 paisa

        return Khalti::initiate($return_url, $purchase_order_id, $purchase_order_name,  $amount);
    }

    public function verify(Request $request) {
        $pidx = $request->get('pidx');
        return Khalti::lookup($pidx);
    }


}
```

## Installation

You can install the package via composer:

```bash
composer require neputertech/khalti
```

The package will automatically register itself.

You can publish the config with:

```bash
php artisan vendor:publish --tag=khalti-config
```


This is the contents of the published config file:
```php
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
```

## Update .env with your khalti credentials
This credentals are provided with merchant dashboard. 

set debug flag to false in config to use live khalti 

```bash
KHALTI_DEBUG=true # Set this flag to false to use khatli in production
KHALTI_LIVE_PUBLIC_KEY=
KHALTI_LIVE_SECRET_KEY=
KHALTI_TEST_PUBLIC_KEY=
KHALTI_TEST_SECRET_KEY=
```



## Usage

The basic concept of this package is that you can integrate Khalti ePayment Gateway (NEW) to your laravel applications and initiate/verify transactions 
