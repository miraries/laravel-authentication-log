<?php

use Illuminate\Support\Facades\Route;
use Yadahan\AuthenticationLog\Controllers\AuthenticationLogController;

Route::get('api/authentications', AuthenticationLogController::class);
