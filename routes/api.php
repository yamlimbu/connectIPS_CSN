<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ConnectIPSGatewayController;
use App\Http\Controllers\Api\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::post('/transaction-detail', [ConnectIPSGatewayController::class, 'getTransactionDetails']);
    Route::post('/store-transaction-log', [TransactionController::class, 'storeTransaction']);

    Route::post('/payment-validation', [ConnectIPSGatewayController::class, 'getPaymentValidation']);
    Route::post('/generate-token', [ConnectIPSGatewayController::class, 'generateToken']);
    Route::post('/payment-initialize', [ConnectIPSGatewayController::class, 'paymentInitialize']);
    Route::get('/payment-constants', [ConnectIPSGatewayController::class, 'paymentConstants']);
    Route::get('/generate-token', [ConnectIPSGatewayController::class, 'generateToken']);
    Route::get('/generate-txnid-referenceid', [ConnectIPSGatewayController::class, 'generateTxnidReferenceId']);

});



