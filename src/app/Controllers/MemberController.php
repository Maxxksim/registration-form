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
        http_response_code(200);
        echo json_encode(['backStep' => $this->getStep('back')]);
    }

    private function getStep(string $where): string
    {
        $currentStep = array_search($_SESSION['steps']['current'], $this->steps, true);

        if ($currentStep === false) {
            http_response_code(400);
            echo json_encode(['errors' => ['step' => 'No step available.']]);
            exit();
        }

        if ($where === 'next') {
            $doStep = $currentStep + 1;
        } else {
            $doStep = $currentStep - 1;
        }

        $_SESSION['steps']['current'] = $this->steps[$doStep];

        return $this->steps[$doStep];
    }

    public function stepOne(): void
    {
        $memberRequest = new MemberStepOneRequest($this->request->validator, $this->request->get, $this->request->post, $this->request->files, $this->request->server);
        $member = new Member($this->db, $this->phoneNumberUtil, $this->countries);
        $result = $memberRequest->validated();
        $validatedData = $result['validatedData'];

        if ($errors = $result['errors']) {
            http_response_code(422);
            echo json_encode(['errors' => $errors]);
            exit();
        }

        if (isset($_SESSION['steps']['data'])) {
            $member->update('email', $_SESSION['steps']['data']['email'], $validatedData);
            $_SESSION['steps']['data'] = array_merge($_SESSION['steps']['data'], $validatedData);

        } else {
            $member->create($validatedData);
            $_SESSION['steps']['data'] = array_merge($validatedData);
        }
        echo json_encode(['nextStep' => $this->getStep('next')]);
        exit();
    }

    public function stepTwo(): void
    {
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
        $_SESSION['steps']['data'] = array_merge($_SESSION['steps']['data'], $validatedData);

        $countMembers = count($member->getMembers());

        echo json_encode(['nextStep' => $this->getStep('next'), 'countMembers' => $countMembers]);
        exit();
    }
}