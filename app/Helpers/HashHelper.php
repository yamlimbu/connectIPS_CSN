<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Storage;

class HashHelper
{
    public static function generateHash($string)
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
}
