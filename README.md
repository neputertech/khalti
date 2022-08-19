# Laravel Khalti 

This package can help you inigrate khalti new ePayment Gateway (NEW) to your php application.

Here is an example of how you can initiate khalti transaction:

```php
...
use Khalti\Khalti;

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
composer require 735l4/khalti
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
    'live_public_key' => '', // public key from khalti
    'live_secret_key' => '' // secret key from khalti
];
```



## Usage

The basic concept of this package is that you can integrate khalti ePayment Gateway (NEW) to your laravel applications and initiate/vetify transactions 