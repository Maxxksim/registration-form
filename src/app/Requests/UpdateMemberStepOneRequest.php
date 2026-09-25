<?php

declare(strict_types=1);

namespace Src\app\Requests;

class UpdateMemberStepOneRequest extends MemberStepOneRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'email' => ['required', 'email', 'max:255'],
        ]);
    }
}