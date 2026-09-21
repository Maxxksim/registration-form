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
        $member = new Member($this->db);
        $countMembers = count($member->getMembers());
        if (!isset($_SESSION['steps']['current'])) {
            $_SESSION['steps']['current'] = $this->steps[0];
        }
        $steps = $_SESSION['steps'];

        $shareData = $this->getShareData();

        $this->view->view('home', ['countries' => $this->countries, 'steps' => $steps, 'countMembers' => $countMembers, 'shareData' => $shareData]);
    }

    private function getShareData(): array
    {
        return [
            'Facebook' => "https://www.facebook.com/sharer/sharer.php?u={$this->config->config('sharing.url')}",
            'X' => "https://x.com/intent/tweet?text={$this->config->config('sharing.text')}&url={$this->config->config('sharing.url')}"
        ];
    }

    public function index(): void
    {
        session_destroy();

        $this->redirect('/start');
    }

    public function shareData(): void
    {
        $shareData = $this->getShareData();
        http_response_code(200);
        echo json_encode(['shareData' => $shareData]);
        exit();
    }
}