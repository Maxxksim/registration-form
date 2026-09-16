<?php

declare(strict_types=1);

namespace Src\app\Controllers;

use Src\app\Requests\PersonRequest;
use Src\Controller\Controller;

class PersonController extends Controller
{
    public function register(): void
    {
        $personRequest = new PersonRequest($this->request->validator, $this->request->get, $this->request->post, $this->request->files, $this->request->server);

        $result = $personRequest->validated();
        $_SESSION['old_data'] = $result['validatedData'];

        if ($errors = $result['errors']) {
            $_SESSION['errors'] = $errors;;
            $this->redirect('/');
        }
    }
}