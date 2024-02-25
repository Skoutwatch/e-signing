<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'company_name' => $this->company_name,
            'type' => $this->type,
            'logo' => $this->logo,
            'email' => $this->email,
            'phone' => $this->phone,
            'verify_me_email' => $this->verify_me_email,
            'verify_me_city' => $this->verify_me_city,
            'verify_me_state' => $this->verify_me_state,
            'verify_me_lga' => $this->verify_me_lga,
            'classification' => $this->classification,
            'registration_company_number' => $this->registration_company_number,
            'registration_date' => $this->registration_date,
            'is_verified' => $this->is_verified ? true : false,
            'address' => $this->address,
            'branch_address' => $this->branch_address,
            'head_office' => $this->head_office,
            'lga' => $this->lga,
            'affiliates' => $this->affiliates,
            'share_capital' => $this->share_capital,
            'share_capital_in_words' => $this->share_capital_in_words,
            'status' => $this->status,
            'country_id' => $this->country_id,
            'state_id' => $this->state_id,
            'city_id' => $this->city_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
