<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAccessTokenRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'clientId' => 'required|string',
            'secretKey' => 'required|string',
        ];
    }
}
