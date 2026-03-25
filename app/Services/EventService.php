<?php

namespace App\Services;

use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Storage;
use App\Models\EventRegistrationHold;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
class EventService
{
    public function getClientDetails(Request $request)
    {
        $agent = new Agent();

        // Get device information
        $device = $agent->device();
        $platform = $agent->platform();
        $platformVersion = $agent->version($platform);
        $browser = $agent->browser();
        $browserVersion = $agent->version($browser);
        $isMobile = $agent->isMobile();
        $isTablet = $agent->isTablet();
        $isDesktop = $agent->isDesktop();
        $isBot = $agent->isRobot();
        $ipAddress = $request->ip();

        // Check if the device is iPhone or Android
        $isIphone = $agent->is('iPhone');
        $isAndroid = $agent->is('AndroidOS');

        // Return as an associative array
        return [
            'device' => $device,
            'platform' => $platform,
            'platform_version' => $platformVersion,
            'browser' => $browser,
            'browser_version' => $browserVersion,
            'is_mobile' => $isMobile,
            'is_tablet' => $isTablet,
            'is_desktop' => $isDesktop,
            'is_bot' => $isBot,
            'ip_address' => $ipAddress,
            'is_iphone' => $isIphone,
            'is_android' => $isAndroid,
        ];
    }


    function generateHash($string)
{
    $fileName = env('PFX_FILE_PATH'); // only filename, e.g., certificate.pfx
    $password = env('CONNECTIPS_PFX_PASSWORD');

    // Normalize path for Windows
    $fullPath = storage_path('app/private/' . $fileName);
    $fullPath = str_replace('/', DIRECTORY_SEPARATOR, $fullPath);

    // Debug: check file existence
    if (!file_exists($fullPath)) {
        throw new \Exception("PFX file not found at: " . $fullPath);
    }

    // Debug: check file size
    $fileSize = filesize($fullPath);
    if ($fileSize === 0) {
        throw new \Exception("PFX file is empty or corrupted: " . $fullPath);
    }

    // Read PFX file
    $cert_store = file_get_contents($fullPath);
    if ($cert_store === false) {
        throw new \Exception("Unable to read PFX file: " . $fullPath);
    }

    // Debug: show first few bytes
    // dd(bin2hex(substr($cert_store, 0, 16)));

    $cert_info = [];
    if (!openssl_pkcs12_read($cert_store, $cert_info, $password)) {
        throw new \Exception("Invalid PFX or wrong password. 
            File exists: " . file_exists($fullPath) . "
            File size: $fileSize bytes
            Password length: " . strlen($password));
    }

    if (empty($cert_info['pkey'])) {
        throw new \Exception("Private key not found in PFX.");
    }

    $private_key = openssl_pkey_get_private($cert_info['pkey']);
    if (!$private_key) {
        throw new \Exception("Unable to extract private key from PFX.");
    }

    // Sign the input string
    if (!openssl_sign($string, $signature, $private_key, OPENSSL_ALGO_SHA256)) {
        throw new \Exception("Signing failed.");
    }

    openssl_free_key($private_key);

    return base64_encode($signature);
}



    function xxxxxgenerateHash($string)
{
    $filePath = env('PFX_FILE_PATH'); // only filename
    $password = env('CONNECTIPS_PFX_PASSWORD');


    $fullPath = storage_path('app/private/' . $filePath);
    dd($fullPath);


    if (!file_exists($fullPath)) {
        throw new \Exception("PFX file not found at: " . $fullPath);
    }

    $cert_store = file_get_contents($fullPath);

    if (!$cert_store) {
        throw new \Exception("Unable to read PFX file.");
    }

    if (!openssl_pkcs12_read($cert_store, $cert_info, $password)) {
        throw new \Exception("Invalid PFX or wrong password.");
    }

    if (empty($cert_info['pkey'])) {
        throw new \Exception("Private key not found in PFX.");
    }

    $private_key = openssl_pkey_get_private($cert_info['pkey']);

    if (!$private_key) {
        throw new \Exception("Unable to extract private key.");
    }

    if (!openssl_sign($string, $signature, $private_key, OPENSSL_ALGO_SHA256)) {
        throw new \Exception("Signing failed.");
    }

    openssl_free_key($private_key);

    return base64_encode($signature);
}
    function OldgenerateHash($string)
    {

        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        $opensslConfPath = env('OPENSSL_CONF');
        putenv("OPENSSL_CONF=$opensslConfPath");

        date_default_timezone_set("Asia/Kathmandu");

        // Try to locate certificate file
        $filePath = env('PFX_FILE_PATH');
        $fullPath = storage_path('app/private/' . $filePath);

        if (!Storage::disk('private')->exists($filePath)) {
            echo "Error: Unable to read the cert file at path: $filePath\n";
            echo "Full path: $fullPath\n";
        }         // Try to locate certificate file
        $cert_store = Storage::disk('private')->get($filePath);
        // Try to read certificate file
        $password = env('CONNECTIPS_PFX_PASSWORD');
        // Try to read the certificate file
        if (openssl_pkcs12_read($cert_store, $cert_info, $password)) {
            if (isset($cert_info['pkey']) && $private_key = openssl_pkey_get_private($cert_info['pkey'])) {
                $array = openssl_pkey_get_details($private_key);
                //print_r($array);  // Print the details of the private key
            } else {
                echo "Error: Unable to extract private key from the certificate store.\n";
            }
        } else {
            echo "Error: Unable to read the cert store. Check the password or file content.\n";
            print_r(error_get_last());
            echo "OpenSSL Error: " . openssl_error_string() . "\n";
        }
        $hash = "";
        if (openssl_sign($string, $signature, $private_key, "sha256WithRSAEncryption")) {
            $hash = base64_encode($signature);
            openssl_free_key($private_key);
        } else {
            echo "Error: Unable openssl_sign";
            exit;
        }
        return $hash;
    }

    public function validatePaymentAndStore($txnid)
    {
        $hold = EventRegistrationHold::where('txnid', $txnid)->first();

        // call api for payment validation

        // Assuming the request is successful and you want to make a Guzzle API call
        if ($hold) {

            $client = new Client();
            try {
                $payload = ['referenceId' => $txnid, 'txnAmt' => $hold->txnamt ];

                $response = $client->request('POST', url('/api/v1/payment-validation'), [
                    'json' => $payload,
                    'verify' => false,
                ]);
                $dd($response);
                // Get the response body
                $body = $response->getBody();
                $content = $body->getContents();

                // echo "Status Code: $statusCode\n";
                // echo "Response Body: $body\n";
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                echo "Request failed: " . $e->getMessage() . "\n";
            }
        }
    }
}
