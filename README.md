# Laravel Khalti 

This package can help you integrate Khalti new ePayment Gateway (NEW) to your php application.

[Khalti ePay Docs](https://docs.khalti.com/khalti-epayment/)

Here is an example of how you can initiate Khalti transaction:

```php
...
use Neputer\Facades\Khalti;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller {
    ...
    public function pay() {
        $return_url = "http://example.com/verify";
        $purchase_order_id = "your_transaction_id"; // example 123567;
        $purchase_order_name = "your_order_name"; // example Transaction: 1234,
        $amount = 1000; // Your total amount in paisa Rs 1 = 100 paisa

        $response =  Khalti::initiate($return_url, $purchase_order_id, $purchase_order_name,  $amount);

        // Custom handle of khalti response

        return Redirect::to($response->payment_url);
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
    'debug' => env('KHALTI_DEBUG', true), // set false to run on live khalti url
    'website_url' => 'https://example.com', // your website url
    'public_key' => env('KHALTI_PUBLIC_KEY', ''), // public key from khalti
    'secret_key' => env('KHALTI_SECRET_KEY', ''), // secret key from khalti
];
```

## Update .env with your khalti credentials
This credentals are provided with merchant dashboard. 

set debug flag to false in config to use live khalti 

```bash
KHALTI_DEBUG=true # Set this flag to false to use khatli in production
KHALTI_PUBLIC_KEY=
KHALTI_SECRET_KEY=
```



## Usage

The basic concept of this package is that you can integrate Khalti ePayment Gateway (NEW) to your laravel applications and initiate/verify transactions 
