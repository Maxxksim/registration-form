<?php

declare(strict_types=1);

namespace Src\app\Models;

use Src\Model\Model;

class Member extends Model
{
    public function create($data): void
    {
        $this->insert('members', [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'birthdate' => $data['birthdate'],
            'report_subject' => $data['report_subject'],
            'phone' => $data['phone'],
            'country' => $data['country'],
            'email' => $data['email'],
        ]);
    }

    public function getMembers(): array
    {
        $preparedData = [];
        $sql = 'SELECT path_to_photo, first_name, last_name, report_subject, email FROM members';
        $stmt = $this->db->pdo->query($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}