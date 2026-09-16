<?php

declare(strict_types=1);

namespace Src\app\Controllers;

use Src\Controller\Controller;
use Src\View\View;

class HomeController extends Controller
{
    public function index(): void
    {
        $errors = $_SESSION['errors'] ?? null;
        $steps = $_SESSION['steps'] ?? ['current' => 'step1'];
        $this->view->view('home', ['countries' => $this->countries, 'errors' => $errors, 'steps' => $steps]);
    }
}