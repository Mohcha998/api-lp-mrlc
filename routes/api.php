<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProspectParentController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\PaymentSpController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::apiResource('prospect', ProspectParentController::class);
Route::apiResource('branches', BranchController::class);
Route::apiResource('programs', ProgramController::class);
Route::apiResource('payment-sps', PaymentSpController::class);
Route::post('/create-invoice', [PaymentSpController::class, 'createInvoice']);
Route::post('/callback', [PaymentSpController::class, 'handleCallback']);
