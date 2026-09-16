<?php

declare(strict_types=1);

use Src\app\Controllers\HomeController;
use Src\app\Controllers\MemberController;
use Src\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::post('/register', [MemberController::class, 'register']),
    Route::get('/members', [MemberController::class, 'index'])
];