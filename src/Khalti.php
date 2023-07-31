<?php


namespace Neputer;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;

class Khalti
{
    private $publicKey;
    private $secretKey;
    private $debug;
    private $autoRedirect;
    private $baseUrl;

    public function __construct()
    {
        $this->debug =  Config::get('khalti.debug');
        $this->autoRedirect = Config::get('khalti.auto_redirect');

        if(!$this->debug) {
            $this->publicKey =  Config::get('khalti.live_public_key');
            $this->secretKey =  Config::get('khalti.live_secret_key');
        } else {
            $this->publicKey =  Config::get('khalti.test_public_key');
            $this->secretKey = Config::get('khalti.test_secret_key');
        }


        $this->baseUrl = $this->debug
            ? 'https://a.khalti.com/api/v2/epayment/initiate/'
            : 'https://khalti.com/api/v2/epayment/initiate/';
    }

    public function initiate(string $return_url, string $purchase_order_id, string $purchase_order_name, int $amount, ?array $customer_info = null, ?array $amount_breakdown = null,  ?array $product_details = null)
    {
        $private_key = $this->secretKey;

        $auto_redirect = $this->autoRedirect;

        $request_data = [
            'return_url' => $return_url,
            'website_url' => Config::get('khalti.website_url'),
            'amount' => $amount,
            'purchase_order_id' => $purchase_order_id,
            'purchase_order_name' => $purchase_order_name,
        ];

        if ($customer_info) {
            $request_data['customer_info'] = $customer_info;
        }

        if ($amount_breakdown) {
            $request_data['amount_breakdown'] = $amount_breakdown;
        }

        if ($product_details) {
            $request_data['amount_breakdown'] = $amount_breakdown;
        }

        $base_url = $this->baseUrl;
        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $base_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request_data),

                CURLOPT_HTTPHEADER => array(
                    "Authorization: Key ${private_key}",
                    'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);

            $response = json_decode($response); // response in php object

            if ($auto_redirect && isset($response->payment_url)) {
                return Redirect::to($response->payment_url);
            }

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function lookup(string $pidx)
    {
        $private_key = $this->secretKey;
        $base_url = $this->baseUrl;

        try {
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $base_url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode([
                    'pidx' => $pidx
                ]),

                CURLOPT_HTTPHEADER => array(
                    "Authorization: Key ${private_key}",
                    'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            $response = json_decode($response, true); //conv in array

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
