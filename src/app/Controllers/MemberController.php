<?php

declare(strict_types=1);

namespace Src\app\Controllers;

use Src\app\Models\Member;
use Src\app\Requests\MemberStepOneRequest;
use Src\app\Requests\MemberStepTwoRequest;
use Src\app\Requests\UpdateMemberStepOneRequest;
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
        if (!isset($_SESSION['steps']['current']) || $_SESSION['steps']['current'] !== 'step2') {
            http_response_code(403);
            echo json_encode(['error' => 'You must be on the step 2']);
            exit();
        }
        $_SESSION['steps']['current'] = 'step1';
        http_response_code(200);
        echo json_encode(['backStep' => 'step1']);
    }

    public function stepOne(): void
    {
        if (!isset($_SESSION['steps']['current']) || $_SESSION['steps']['current'] !== 'step1') {
            http_response_code(403);
            echo json_encode(['error' => 'You must be on the step 1']);
            exit();
        }
        $member = new Member($this->db, $this->phoneNumberUtil, $this->countries);

        $savedEmail = $_SESSION['steps']['data']['email'] ?? null;
        $newEmail = $this->request->getData()['email'] ?? '';
        $normalizeNewEmail = mb_strtolower(mb_trim($newEmail));

        if ($savedEmail && $savedEmail === $normalizeNewEmail) {
            $memberRequest = new UpdateMemberStepOneRequest($this->request->validator, $this->request->get, $this->request->post, $this->request->files, $this->request->server);
            $isUpdate = true;
        } else {
            $memberRequest = new MemberStepOneRequest($this->request->validator, $this->request->get, $this->request->post, $this->request->files, $this->request->server);
            $isUpdate = false;
        }

        $result = $memberRequest->validated();

        if ($errors = $result['errors']) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            exit();
        }

        $validatedData = $result['validatedData'];

        if ($isUpdate) {
            $savedData = $member->update('email', $_SESSION['steps']['data']['email'], $validatedData);
            $_SESSION['steps']['data'] = array_merge($_SESSION['steps']['data'], $savedData);
        } else {
            $savedData = $member->create($validatedData);
            $_SESSION['steps']['data'] = $savedData;
        }
        $_SESSION['steps']['current'] = 'step2';

        echo json_encode(['nextStep' => 'step2']);
        exit();
    }

    public function stepTwo(): void
    {
        if (!isset($_SESSION['steps']['current']) || $_SESSION['steps']['current'] !== 'step2') {
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
        session_destroy();
        echo json_encode(['nextStep' => 'stepThanks', 'countMembers' => $countMembers]);
        exit();
    }
}