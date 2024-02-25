<?php

namespace App\Http\Requests\Document;

use Illuminate\Foundation\Http\FormRequest;

class GeneratePdfRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'document.*' => 'nullable|base64file',
            'text' => 'nullable|string',
            'image' => 'nullable',
        ];
    }
}
