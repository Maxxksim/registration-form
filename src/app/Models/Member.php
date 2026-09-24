<?php

declare(strict_types=1);

namespace Src\app\Models;

use libphonenumber\PhoneNumberFormat;
use Src\Model\Model;

class Member extends Model
{
    protected static string $table = 'members';
    protected static array $fillable = ['first_name', 'last_name', 'birthdate', 'report_subject', 'phone', 'country', 'email', 'path_to_photo', 'company', 'position', 'about_me'];

    public function getMembers(): array
    {
        $sql = 'SELECT path_to_photo, first_name, last_name, report_subject, email FROM members';
        $stmt = $this->db->pdo->query($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }
}