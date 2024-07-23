<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QrCodeController extends Controller
{
    public function show($filename)
    {
        $path = storage_path('app/public/qrcodes/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path);
    }
}
