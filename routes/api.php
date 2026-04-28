<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VoiceComplaintController;

Route::post('/voice-complaint', [VoiceComplaintController::class, 'store']);
