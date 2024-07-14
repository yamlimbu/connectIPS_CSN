<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;


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
Route::get('/event_register', [EventController::class, 'event_register'])->name('event_register');


// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/register', function () {
//     return view('register');
// });
