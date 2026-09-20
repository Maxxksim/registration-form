<?php

namespace Src\app\Requests;

use Src\Request\Request;

class MemberStepTwoRequest extends Request
{
    public function rules(): array
    {
        return [
            'company' => ['string', 'max:255'],
            'position' => ['string', 'max:255'],
            'about_me' => ['string', 'max:500'],
            'photo' => ['file', 'type:image/png,image/jpeg,image/webp', 'size:1024']
        ];
    }
}