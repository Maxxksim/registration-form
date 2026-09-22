<?php

declare(strict_types=1);

namespace Src\app\Controllers;

use Src\app\Models\Member;
use Src\app\Requests\MemberStepOneRequest;
use Src\Controller\Controller;

class HomeController extends Controller
{
    public function startOver(): void
    {
        $member = new Member($this->db, $this->phoneNumberUtil, $this->countries);
        $countMembers = count($member->getMembers());
        if (!isset($_SESSION['steps']['current'])) {
            $_SESSION['steps']['current'] = $this->steps[0];
        }
        $steps = $_SESSION['steps'];

        $shareData = [
            'url' => $this->config->config('sharing.url'),
            'text' => $this->config->config('sharing.text'),
        ];

        $this->view->view('home', ['countries' => $this->countries, 'steps' => $steps, 'countMembers' => $countMembers, 'shareData' => $shareData]);
    }

    public function index(): void
    {
        session_destroy();

        $this->redirect('/start');
    }
}