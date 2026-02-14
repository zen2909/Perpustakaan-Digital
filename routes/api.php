<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentFineController;

Route::post('/payment/webhook', [PaymentFineController::class, 'webhook']);