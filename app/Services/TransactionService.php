<?php

namespace App\Services;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Storage;

class TransactionService
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

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $opensslConfPath = env('OPENSSL_CONF');
    putenv("OPENSSL_CONF=$opensslConfPath");

    date_default_timezone_set("Asia/Kathmandu");

    // Try to locate certificate file
    $filePath = 'CREDITOR.pfx';
    $fullPath = storage_path('app/private/' . $filePath);

    if (!Storage::disk('private')->exists($filePath)) {
        echo "Error: Unable to read the cert file at path: $filePath\n";
        echo "Full path: $fullPath\n";
    }         // Try to locate certificate file
    $cert_store = Storage::disk('private')->get($filePath);
    // Try to read certificate file
    $password = "123";
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
}
