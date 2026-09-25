<?php

declare(strict_types=1);

namespace Src\CSRF;

class CSRFToken
{
    public function generateToken(): void
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    }

    public function checkAccess(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $headers = getallheaders();
            if (!isset($headers['X-CSRF-Token']) ||
                !hash_equals($_SESSION['csrf_token'], $headers['X-CSRF-Token'])) {
                http_response_code(403);
                echo json_encode(['errors' => ['permission' => 'Your session has expired.']]);
                exit();
            }
        }
    }
}