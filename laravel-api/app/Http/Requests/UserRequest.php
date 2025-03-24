<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string',
            'email' => 'string',
            'email_verified_at' => 'date',
            'password' => 'string',
            'remember_token' => 'string',
        ];
    }
}
