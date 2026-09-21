<?php

declare(strict_types=1);

use Src\app\Controllers\HomeController;
use Src\app\Controllers\MemberController;
use Src\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::get('/start', [HomeController::class, 'startOver']),
    Route::get('/members', [MemberController::class, 'index']),
    Route::get('/register/steps/back', [MemberController::class, 'backStep']),
    Route::post('/register/steps/one', [MemberController::class, 'stepOne']),
    Route::post('/register/steps/two', [MemberController::class, 'stepTwo']),
    Route::get('/sharing', [HomeController::class, 'shareData'])
];