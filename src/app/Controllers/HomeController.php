<?php

declare(strict_types=1);

namespace Src\app\Controllers;

use Src\Controller\Controller;
use Src\View\View;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view->view('home', ['countries' => $this->countries]);
    }
}