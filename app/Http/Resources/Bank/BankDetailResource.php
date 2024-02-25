<?php

namespace App\Http\Resources\Bank;

use Illuminate\Http\Resources\Json\JsonResource;

class BankDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'bank_id' => $this->bank ? $this->bank->id : 'N/A',
            'bank_name' => $this->bank ? $this->bank->name : 'N/A',
            'bank_account_name' => $this->bank_account_name,
            'bank_account_number' => $this->bank_account_number,
        ];
    }
}
