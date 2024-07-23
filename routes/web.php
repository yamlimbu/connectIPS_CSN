<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\QrCodeController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [EventController::class, 'index'])->name('events.index');
Route::get('/register/{event_id}', [EventController::class, 'register'])->name('event.register');
Route::get('/details/{event_id}', [EventController::class, 'details'])->name('event.details');
Route::post('/event_register', [EventController::class, 'event_register'])->name('event_register');
Route::post('/final_submit', [EventController::class, 'final_submit'])->name('final_submit');

Route::get('/preview', [EventController::class, 'preview'])->name('preview');
Route::get('/event-register/success', [EventController::class, 'success'])->name('success');
Route::get('/event-register/fail', [EventController::class, 'fail'])->name('fail');


// Route::post('/store-transaction-log', [TransactionController::class, 'storeTransaction']);
// Route::post('/transaction-details', [TransactionController::class, 'getTransactionDetails']);
Route::get('/qr-code/{filename}', [QrCodeController::class, 'show']);
