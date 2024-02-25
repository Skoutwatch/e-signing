<?php

namespace App\Http\Requests\Verify;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Quore Id Form Request Fields",
 *      description="Store Quore Id Form Request body data",
 *      type="object",
 *      required={"first_name"}
 * )
 */
class StoreQoreIdFaceMatchVerificationFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="Verify type",
     *      description="Verify type",
     *      example="bvn,nin,drivers_license,vnin"
     * )
     *
     * @var string
     */
    public $type;

    /**
     * @OA\Property(
     *      title="photoBase64",
     *      description="photoBase64",
     *      example="/9j/4AAQSkZJRgABAQAAAQABAAD/2wBDABALDA4MChAODQ4SERATGCgaGBYWGDEjJR0oOjM9PDkzODdASFxOQERXRTc4UG1RV19iZ2hnPk1xeXBkeFxlZ2P/2wBDARESEhgVGC8aGi9jQjhCY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2NjY2P/wAARCAmQCZADASIAAhEBAxEB/8QAGwABAQEBAQEBAQAAAAAAAAAAAAECAwQFBgf/xAA/EAACAgEEAQMDAwIFAwIGAAcAAQIRIQMSMUFRBCJhEzJxBYGRQqEGFCNSsWLB0TNyFSRDguHwkvE0orJTFv/EABkBAQEBAQEBAAAAAAAAAAAAAAABAgMEBf/EACQRAQEBAQACAgICAwEBAAAAAAABEQISMQMhBEETUSIjcTJh/9oADAMBAAIRAxEAPwD9YADg7AAAFHIChAAFgAACAooACAAAABEG"
     * )
     *
     * @var string
     */
    public $photoBase64;

    /**
     * @OA\Property(
     *      title="idNumber",
     *      description="idNumber",
     *      example="95888168924"
     * )
     *
     * @var string
     */
    public $idNumber;

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
            'type' => 'required|In:bvn,nin,drivers_license,vnin',
            'photoBase64' => 'required|base64image',
            'idNumber' => 'required|string',
        ];
    }
}
