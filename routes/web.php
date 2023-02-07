<?php

use App\Http\Controllers\UploadDeliveryController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [UploadDeliveryController::class, 'view'])->name('upload-view');
Route::post('/upload',[UploadDeliveryController::class, 'upload'])->name('upload');
Route::post('/process-files', [UploadDeliveryController::class, 'processFiles'])->name('process-files');
