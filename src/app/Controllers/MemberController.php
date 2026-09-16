<?php

declare(strict_types=1);

namespace Src\app\Controllers;

use Src\app\Models\Member;
use Src\app\Requests\PersonRequest;
use Src\Controller\Controller;

class PersonController extends Controller
{
    public function register(): void
    {
        $personRequest = new PersonRequest($this->request->validator, $this->request->get, $this->request->post, $this->request->files, $this->request->server);
        $person = new Member($this->db);
        $result = $personRequest->validated();
        $_SESSION['old_data'] = $result['validatedData'];

        if ($errors = $result['errors']) {
            $_SESSION['errors'] = $errors;;
            $this->redirect('/');
        }

        $person->create($result['validatedData']);
        $this->redirect('/members');
    }

    public function index(): void
    {
        $person = new Member($this->db);
        $members = $person->getMembers();

        $this->view->view('members', ['members' => $members]);
    }


}