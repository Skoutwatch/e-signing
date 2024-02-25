<?php

namespace App\Http\Requests\SignaturePrint;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Store Print Form Request Fields",
 *      description="Store Print Request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class StorePrintFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="File",
     *      description="required_if:type,==,Initial|required_if:type,==,Signature|required_if:type,==,Stamp|required_if:type,==,Seal|required_if:type,==,Photograph|required_if:type,==,Thumbprint|string|base64image",
     *      example="base64File"
     * )
     *
     * @var string
     */
    public $file;

    /**
     * @OA\Property(
     *      title="type",
     *      description="type",
     *      example="Initial|Signature|NotaryStamp|NotaryTraditionalSeal|NotaryDigitalSeal|CompanyStamp|CompanySeal|Photograph|Camera|LeftThumbFinger|LeftPointerFinger|LeftMiddleFinger|LeftRingFinger|LeftPinkyFinger|LeftPinkyFinger|RightThumbFinger|RightPointerFinger|RightMiddleFinger|RightRingFinger|RightPinkyFinger|Text"
     * )
     *
     * @var string
     */
    public $type;

    /**
     * @OA\Property(
     *      title="category",
     *      description="category",
     *      example="Draw|Type|Upload"
     * )
     *
     * @var string
     */
    public $category;

    /**
     * @OA\Property(
     *      title="value",
     *      description="required_if:type,==,Text",
     *      example="Ade"
     * )
     *
     * @var string
     */
    public $value;

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'type' => 'required|in:Date,Fullname,Initial,Signature,NotaryStamp,NotaryTraditionalSeal,NotaryDigitalSeal,CompanyStamp,CompanySeal,Photograph,Camera,LeftThumbFinger,LeftPointerFinger,LeftMiddleFinger,LeftRingFinger,LeftPinkyFinger,RightThumbFinger,RightPointerFinger,RightMiddleFinger,RightRingFinger,RightPinkyFinger,Text',
            'category' => 'required|in:Draw,Type,Upload',
            'file' => 'nullable|required_if:type,==,Initial|required_if:type,==,Signature|required_if:type,==,Stamp|required_if:type,==,Seal|required_if:type,==,Photograph|required_if:type,==,Thumbprint|string|base64image',
            'value' => 'required_if:type,==,Text',
        ];
    }
}
