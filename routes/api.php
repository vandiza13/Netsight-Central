<?php

use App\Http\Controllers\LicenseApiController;
use Illuminate\Support\Facades\Route;

Route::post('/v1/validate-license', [LicenseApiController::class, 'validateLicense'])
    ->withoutMiddleware('throttle:api');
