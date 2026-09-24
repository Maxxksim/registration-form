<?php

declare(strict_types=1);

namespace Src\app\Controllers;

use Src\app\Models\Member;
use Src\app\Requests\MemberStepOneRequest;
use Src\app\Requests\MemberStepTwoRequest;
use Src\Controller\Controller;

class MemberController extends Controller
{
    public function index(): void
    {
        $member = new Member($this->db, $this->phoneNumberUtil, $this->countries);
        $members = $member->getMembers();

        $this->view->view('members', ['members' => $members]);
    }

    public function backStep(): void
    {
        if (!isset($_SESSION['steps']['current']) && $_SESSION['steps']['current'] !== 'step2') {
            http_response_code(403);
            echo json_encode(['error' => 'You must be on the step 2']);
            exit();
        }
        http_response_code(200);
        echo json_encode(['backStep' => 'step1']);
    }

    public function stepOne(): void
    {
        if (!isset($_SESSION['steps']['current']) && $_SESSION['steps']['current'] !== 'step1') {
            http_response_code(403);
            echo json_encode(['error' => 'You must be on the step 1']);
            exit();
        }
        $memberRequest = new MemberStepOneRequest($this->request->validator, $this->request->get, $this->request->post, $this->request->files, $this->request->server);
        $member = new Member($this->db, $this->phoneNumberUtil, $this->countries);
        $result = $memberRequest->validated();
        $validatedData = $result['validatedData'];

        if ($errors = $result['errors']) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            exit();
        }

        $savedEmail = $_SESSION['steps']['data']['email'] ?? null;
        $newEmail = $validatedData['email'] ?? null;

        if ($savedEmail && $savedEmail === $newEmail) {
            $savedData = $member->update('email', $_SESSION['steps']['data']['email'], $validatedData);
            $_SESSION['steps']['data'] = array_merge($_SESSION['steps']['data'], $savedData);
        } else {
            $savedData = $member->create($validatedData);
            $_SESSION['steps']['data'] = array_merge($savedData);
        }
        $_SESSION['steps']['current'] = ['step2'];
        echo json_encode(['nextStep' => 'step2']);
        exit();
    }

    public function stepTwo(): void
    {
        if (!isset($_SESSION['steps']['current']) && $_SESSION['steps']['current'] !== 'step2') {
            http_response_code(403);
            echo json_encode(['error' => 'You must be on the step 2']);
            exit();
        }
        $memberRequest = new MemberStepTwoRequest($this->request->validator, $this->request->get, $this->request->post, $this->request->files, $this->request->server);
        $member = new Member($this->db, $this->phoneNumberUtil, $this->countries);

        $result = $memberRequest->validated();
        $validatedData = $result['validatedData'];

        if ($errors = $result['errors']) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            exit();
        }

        if (isset($validatedData['photo'])) {
            $pathToPhoto = $this->storage->saveUploadedFile($validatedData['photo']);
            $validatedData = array_merge($validatedData, ['path_to_photo' => $pathToPhoto]);
        }

        $member->update('email', $_SESSION['steps']['data']['email'], $validatedData);

        $countMembers = count($member->getMembers());

        echo json_encode(['nextStep' => 'stepThanks', 'countMembers' => $countMembers]);
        exit();
    }
}