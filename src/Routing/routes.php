<?php

declare(strict_types=1);

use Src\app\Controllers\HomeController;
use Src\app\Controllers\RegisterController;
use Src\Routing\Route;

return [
    Route::get('/', [HomeController::class, 'index']),
    Route::post('/register', [RegisterController::class, 'register'])
];