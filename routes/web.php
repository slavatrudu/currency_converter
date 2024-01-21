<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ConvertController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [ConvertController::class, 'getIndex'])->name('dashboard');
    Route::get('/update-rate', [ConvertController::class, 'getUpdateRate'])->name('update-rate');
    Route::post('/convert', [ConvertController::class, 'postConvert'])->name('convert');
});


require __DIR__.'/auth.php';
