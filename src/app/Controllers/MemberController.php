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

            if (isset($validatedData['photo'])) {
                $pathToPhoto = $this->storage->saveUploadedFile($validatedData['photo']);
                $validatedData = array_merge($validatedData, ['path_to_photo' => $pathToPhoto]);
            }

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
        $step = $this->getStep('back');

        http_response_code(200);
        echo json_encode(['backStep' => $step]);
    }

    public function nextStep(): void
    {

        if (!$this->createOrUpdate()) {
            return;
        }

        $step = $this->getStep('next');

        $response = ['nextStep' => $step];
        if ($step === 'stepThanks') {
            $member = new Member($this->db);
            $response['countMembers'] = count($member->getMembers());
        }

        http_response_code(200);
        echo json_encode($response);
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
}