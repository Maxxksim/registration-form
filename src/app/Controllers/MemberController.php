<?php

declare(strict_types=1);

namespace Src\app\Controllers;

use Src\app\Models\Member;
use Src\app\Requests\MemberRequest;
use Src\Controller\Controller;

class MemberController extends Controller
{
    private function registerStep1(): void
    {
        $memberRequest = new MemberRequest($this->request->validator, $this->request->get, $this->request->post, $this->request->files, $this->request->server);
        $member = new Member($this->db);
        $result = $memberRequest->validated();
        $validatedData = $result['validatedData'];

        if ($errors = $result['errors']) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
        }

        $member->create($validatedData);

        $_SESSION['steps']['step1'] = ['data' => $validatedData];
    }

    public function index(): void
    {
        $member = new Member($this->db);
        $members = $member->getMembers();

        $this->view->view('members', ['members' => $members]);
    }

    public function back(): void
    {
        $_SESSION['steps']['current'] = 'step1';
        http_response_code(200);
        echo json_encode(['backStep' => 'step1']);
    }

    public function next(): void
    {

        if (!isset($_SESSION['steps']['step1'])) {
            $this->registerStep1();
            return;
        }

        $_SESSION['steps']['current'] = 'step2';
        http_response_code(200);
        echo json_encode(['nextStep' => 'step2']);
    }
}