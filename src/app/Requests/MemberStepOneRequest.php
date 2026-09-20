<?php

declare(strict_types=1);

namespace Src\app\Requests;

use Src\Request\Request;

class MemberStepOneRequest extends Request
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'birthdate' => ['required', 'date'],
            'report_subject' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'length:11', 'country-code:1', 'int'],
            'country' => ['required', 'string'],
            'email' => ['required', 'email', 'unique'],
        ];
    }

    public static function messages(): array
    {
        return [
            'birthdate.required' => 'Please select your birthdate',
            'country.required' => 'Please select your country',
            'phone.required' => 'Please enter your phone number',
            'phone.length' => 'Phone number must be 11 characters',
            'phone.int' => 'Phone number must contain numbers only'
        ];
    }
}