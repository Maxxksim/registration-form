<?php

declare(strict_types=1);

namespace Src\app\Requests;

use Src\Request\Request;

class MemberRequest extends Request
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'birthdate' => ['required', 'date'],
            'report_subject' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'length:11'],
            'country' => ['required'],
            'email' => ['required', 'email', 'unique'],
            'company' => ['string', 'max:255'],
            'position' => ['string', 'max:255'],
            'about_me' => ['string'],
            'photo' => ['file', 'type:image/png,image/jpeg,image/webp', 'size:3']
        ];
    }
}