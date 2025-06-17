<?php


namespace Neputer;

use Illuminate\Support\Facades\Config;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;

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
        $this->publicKey =  Config::get('khalti.public_key');
        $this->secretKey =  Config::get('khalti.secret_key');


        $this->baseUrl = $this->debug
            ? 'https://a.khalti.com/api/v2/epayment'
            : 'https://khalti.com/api/v2/epayment';
    }

    public function setDebug(bool $status)
    {
        $this->debug = $status;
        return $this;
    }

    public function setPublicKey(string $publicKey)
    {
        $this->publicKey = $publicKey;
        return $this;
    }

    public function setPrivateKey(string $privateKey)
    {
        $this->privateKey = $privateKey;
        return $this;
    }

    public function initiate(
        string $return_url,
        string $purchase_order_id,
        string $purchase_order_name,
        int $amount,
        ?array $customer_info = [],
        ?array $amount_breakdown = [],
        ?array $product_details = []
    )
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
            $request_data['product_details'] = $product_details;
        }

        $base_url = $this->baseUrl . '/initiate/';
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
                    "Authorization: Key $private_key",
                    'Content-Type: application/json'
                ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);

            $response = (object) json_decode($response); // response in php object

            if(isset($response->error_key)) {
                throw ValidationException::withMessages((array) $response);
            }

            if(isset($response->status_code) && $response->status_code === 401) {
                throw ValidationException::withMessages(['error' => $response->detail])->status(401);
            }

            return $response;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function lookup(string $pidx)
    {
        $private_key = $this->secretKey;
        $base_url = $this->baseUrl . '/lookup/';

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
                    "Authorization: Key $private_key",
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
