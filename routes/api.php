<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here you can register API routes for your application. These routes
| are loaded by the RouteServiceProvider within a group which is
| assigned the "api" middleware group. Feel free to add your
| API endpoints here.
|
*/

// Example: Get authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Sample API Routes
|--------------------------------------------------------------------------
|
| Add more API routes below as needed. You can organize them by
| resource or module, for example:
|
| Route::prefix('users')->group(function () {
|     Route::get('/', [UserController::class, 'index']);
|     Route::post('/', [UserController::class, 'store']);
| });
|
*/

// Example test route
Route::get('/ping', function () {
    return response()->json([
        'message' => 'pong'
    ]);
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Flutterwave Webhook Route (No auth middleware needed - verified by signature)
use App\Http\Controllers\FlutterwaveController;

Route::post('/flutterwave/webhook', [FlutterwaveController::class, 'handleWebhook'])
    ->name('flutterwave.webhook');
