<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use App\Helpers\HashHelper;
use App\Helpers\AgentClientHelper;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;

class ConnectIpsService
{
    // protected $client;
    // protected $baseUrl;
    // protected $appId;
    // protected $password;
    // protected $merchantId;

    public function __construct()
    {
        $this->client = new Client();
        $this->baseUrl = env('CONNECTIPS_BASE_URL', 'https://your_base_url');
        $this->appId = env('APPID', 'MER-550-APP-1');
        $this->password = env('CONNECTIPS_PASSWORD', 'your_password');
        $this->merchantId = env('MERCHANTID', 980);
        $this->appName = env('APPNAME', 980);
    }

    public function getTransactionDetail($referenceId, $txnAmt)
    {
        $appId=$this->appId;
        $merchantId=$this->merchantId;
        $string = "MERCHANTID=$merchantId,APPID=$appId,REFERENCEID=$referenceId,TXNAMT=$txnAmt";
        $token = HashHelper::generateHash($string);
        $payload = [
            'merchantId' => $this->merchantId,
            'appId' => $this->appId,
            'referenceId' => $referenceId,
            'txnAmt' => $txnAmt,
            'token' => $token
        ];

        try {
            $fullUrl = "{$this->baseUrl}/connectipswebws/api/creditor/gettxndetail";

            $response = $this->client->request('POST', $fullUrl, [
                'auth' => [$this->appId, $this->password],
                'json' => $payload,
                'verify' => false,
            ]);

            $responseBody = $response->getBody()->getContents();
            $responseData = json_decode($responseBody, true);
             // Store the request and response in the database
             DB::table('transaction_request_logs')->insert([
                'details' => json_encode([
                    'url' => $fullUrl,
                    'request_payload' => $payload,
                    'response' => $responseData
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'agent' => json_encode([
                    'ip' => request()->ip(),
                    'user_agent' => request()->header('User-Agent'),

                ]),
                'status' => $responseData['status'],
            ]);
            return json_decode($responseBody, true);
        } catch (GuzzleException $e) {
            // Store the error details in the database
            DB::table('transaction_request_logs')->insert([
                'details' => json_encode([
                    'url' => $fullUrl,
                    'request_payload' => $payload,
                    'error_message' => $e->getMessage()
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'agent' => json_encode([
                    'ip' => request()->ip(),
                    'user_agent' => request()->header('User-Agent'),
                ]),
            ]);
            return ['error' => $e->getMessage()];
        }
    }

    public function getPaymentValidation($referenceId, $txnAmt)
    {

        $appId=$this->appId;
        $merchantId=$this->merchantId;
        $string = "MERCHANTID=$merchantId,APPID=$appId,REFERENCEID=$referenceId,TXNAMT=$txnAmt";
        $token = HashHelper::generateHash($string);

        $payload = [
            'merchantId' => $this->merchantId,
            'appId' => $this->appId,
            'referenceId' => $referenceId,
            'txnAmt' => $txnAmt,
            'token' => $token
        ];
        $fullUrl = "{$this->baseUrl}/connectipswebws/api/creditor/validatetxn";

        try {
            $fullUrl = "{$this->baseUrl}/connectipswebws/api/creditor/validatetxn";

            $response = $this->client->request('POST', $fullUrl, [
                'auth' => [$this->appId, $this->password],
                'json' => $payload,
                'verify' => false,
            ]);

            $responseBody = $response->getBody()->getContents();

            $responseData = json_decode($responseBody, true);

            // Store the request and response in the database
            DB::table('transaction_request_logs')->insert([
                'details' => json_encode([
                    'url' => $fullUrl,
                    'request_payload' => $payload,
                    'response' => $responseData
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'agent' => json_encode([
                    'ip' => request()->ip(),
                    'user_agent' => request()->header('User-Agent'),
                ]),
            ]);
            return json_decode($responseBody, true);
        } catch (GuzzleException $e) {
            // Store the error details in the database
            DB::table('transaction_request_logs')->insert([
                'details' => json_encode([
                    'url' => $fullUrl,
                    'request_payload' => $payload,
                    'error_message' => $e->getMessage()
                ]),
                'created_at' => now(),
                'updated_at' => now(),
                'agent' => json_encode([
                    'ip' => request()->ip(),
                    'user_agent' => request()->header('User-Agent'),
                ]),
            ]);
            return ['error' => $e->getMessage()];
        }
    }
}
