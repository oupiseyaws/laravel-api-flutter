<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'category_id' => $this->category_id,
            'category_name' => $this->category->name,
            'transaction_date' => $this->transaction_date,
            'amount' => $this->amount,
            'description' => $this->description,
        ];
    }
}
