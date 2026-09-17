<?php

declare(strict_types=1);

namespace Src\app\Controllers;

use Src\app\Models\Member;
use Src\app\Requests\MemberRequest;
use Src\Controller\Controller;

class MemberController extends Controller
{
    private function createOrUpdate(): bool
    {
        $memberRequest = new MemberRequest($this->request->validator, $this->request->get, $this->request->post, $this->request->files, $this->request->server);
        $member = new Member($this->db);
        $result = $memberRequest->validated();
        $validatedData = $result['validatedData'];

        if ($errors = $result['errors']) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return false;
        }

        if (isset($_SESSION['steps']['data'])) {
            $member->update('email', $_SESSION['steps']['data']['email'], $validatedData);
            $_SESSION['steps']['data'] = array_merge($_SESSION['steps']['data'], $validatedData);
        } else {
            $member->create($validatedData);
            $_SESSION['steps']['data'] = array_merge($validatedData, ['company' => '', 'position' => '', 'about_me' => '']);
        }
        return true;
    }

    public function index(): void
    {
        $member = new Member($this->db);
        $members = $member->getMembers();

        $this->view->view('members', ['members' => $members]);
    }

    public function backStep(): void
    {
        $_SESSION['steps']['current'] = 'step1';
        http_response_code(200);
        echo json_encode(['backStep' => 'step1']);
    }

    public function nextStep(): void
    {

        if (!$this->createOrUpdate()) {
            return;
        }

        $_SESSION['steps']['current'] = 'step2';
        http_response_code(200);
        echo json_encode(['nextStep' => 'step2']);
    }

    public function update(): void
    {
        $memberRequest = new MemberRequest($this->request->validator, $this->request->get, $this->request->post, $this->request->files, $this->request->server);
        $member = new Member($this->db);

        $result = $memberRequest->validated();
        $validatedData = $result['validatedData'];

        if (!isset($_SESSION['steps'])) {
            http_response_code(400);
            echo json_encode(['errors' => ['update' => 'You have to be registered']]);
            return;
        }

        $_SESSION['steps']['data'] = array_merge($_SESSION['steps']['data'], $validatedData);

        if ($errors = $result['errors']) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            return;
        }

        if (isset($validatedData['photo'])) {
            $pathToPhoto = $this->storage->saveUploadedFile($validatedData['photo']);
            $validatedData = array_merge($validatedData, ['path_to_photo' => $pathToPhoto]);
        }

        $member->update('email', $_SESSION['steps']['data']['email'], $validatedData);
        http_response_code(200);

        if ($_SESSION['steps']['current'] == 'step2') {
            $_SESSION['steps']['current'] = 'stepThanks';
            echo json_encode(['nextStep' => 'stepThanks']);
            return;
        }

        echo json_encode(['message' => 'Member has been updated.']);
    }

    public function getCountMember(): void
    {
        $memberRequest = new MemberRequest($this->request->validator, $this->request->get, $this->request->post, $this->request->files, $this->request->server);
        $member = new Member($this->db);
        $countMembers = count($member->getMembers());

        http_response_code(200);
        echo json_encode(['countMembers' => $countMembers]);
        return;
    }
}