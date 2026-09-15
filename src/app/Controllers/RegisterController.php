<?php

declare(strict_types=1);

namespace Src\app\Controllers;

use Src\Controller\Controller;

class RegisterController extends Controller
{
    public function register(): void
    {
        $this->view->view('home');
    }
}