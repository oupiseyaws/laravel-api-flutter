<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'integer',
            'category_id' => 'integer',
            'transaction_date' => 'date',
            'amount' => 'integer',
            'description' => 'string',
        ];
    }
}
