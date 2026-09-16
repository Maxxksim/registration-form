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
        unset($_SESSION['errors']);
        $this->view->view('step1', ['countries' => $this->countries, 'errors' => $errors]);
    }
}