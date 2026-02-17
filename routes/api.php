<?php

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/prayer/{donation}/amin', function (Donation $donation) {
    // Donation acts as Prayer
    $donation->increment('amin_count');
    return response()->json(['amin_count' => $donation->fresh()->amin_count]);
});

Route::post('/midtrans/callback', [App\Http\Controllers\MidtransCallbackController::class, 'handle']);
Route::post('/tripay/callback', [App\Http\Controllers\TripayCallbackController::class, 'handle']);
