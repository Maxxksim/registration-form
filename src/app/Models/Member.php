<?php

declare(strict_types=1);

namespace Src\app\Models;

use libphonenumber\PhoneNumberFormat;
use Src\Model\Model;

class Member extends Model
{
    protected static string $table = 'members';

    protected static function fields(): array
    {
        return [
            'id', 'first_name', 'last_name', 'birthdate', 'report_subject', 'phone', 'country', 'email', 'path_to_photo', 'company', 'position', 'email'
        ];
    }

    public function create($data): void
    {
        $phone = $this->phoneNumberUtil->parse($data['phone']);

        $this->insert('members', [
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'birthdate' => $data['birthdate'],
            'report_subject' => $data['report_subject'],
            'phone' => $this->phoneNumberUtil->format($phone, PhoneNumberFormat::E164),
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