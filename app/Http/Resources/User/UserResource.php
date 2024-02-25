<?php

namespace App\Http\Resources\User;

use App\Http\Resources\Company\CompanyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'initials' => substr($this->first_name, 0, 1).''.substr($this->last_name, 0, 1),
            'email' => $this->email,
            'phone' => $this->phone,
            'bvn' => $this->system_verification ? '**********' : null,
            'image' => config('externallinks.s3_storage_url').$this->image,
            'gender' => $this->gender,
            'address' => $this->address,
            'country' => $this->country,
            'state' => $this->state,
            'city' => $this->city,
            'is_online' => $this->is_online ? true : false,
            'ip_address' => $this->ip_address,
            'identity_type' => $this->identity_type,
            'identity_number' => ($this->identity_type == 'nin' ? $this->nin : ($this->identity_type == 'bvn' ? $this->bvn : ($this->identity_type == 'drivers_license' ? $this->drivers_license_no : null))),
            'nin' => $this->system_verification ? '**********' : null,
            'drivers_license_no' => $this->system_verification ? '**********' : null,
            'dob' => $this->dob,
            'role' => $this->getRoleNames(),
            'system_verification' => $this->system_verification ? true : false,
            'national_verification' => $this->national_verification ? true : false,
            'access_locker_documents' => $this->access_locker_documents ? true : false,

            'permissions' => $this->getPermissionsViaRoles()->pluck('name')->map(function ($permission) {
                return explode('_', $permission);
            })->toArray(),

            'avatar' => $this->avatar != null ? $this->avatar->file_url : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_complete' => $this->is_complete,
            'referral_code' => $this->referral_code,
            'is_business' => $this->is_business ? true : false,

            $this->mergeWhen($this->is_business, [
                'business_plan' => null,
            ]),

            $this->mergeWhen($this->hasRole('Notary'), [
                'notary_verification' => $this->notary_verification ? true : false,
                'notary_commission_number' => $this->notary_commission_number,
            ]),

            $this->mergeWhen($this->hasRole('Company'), [
                'company' => new CompanyResource($this->company),
            ]),
        ];
    }
}
