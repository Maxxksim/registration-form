<?php

declare(strict_types=1);

use Src\app\Controllers\HomeController;
use Src\app\Controllers\MemberController;
use Src\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/start', [HomeController::class, 'startOver']),
    Route::get('/members', [MemberController::class, 'index']),
    Route::get('/register/back', [MemberController::class, 'backStep']),
    Route::post('/register/next', [MemberController::class, 'nextStep']),
];