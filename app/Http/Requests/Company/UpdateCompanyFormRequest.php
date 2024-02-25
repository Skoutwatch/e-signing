<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @OA\Schema(
 *      title="Update company Document Participant Form Request Fields",
 *      description="Update company Participant request body data",
 *      type="object",
 *      required={"name"}
 * )
 */
class UpdateCompanyFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="company name Id",
     *      description="company name  id",
     *      example="Schneider and sons"
     * )
     *
     * @var string
     */
    public $company_name;

    /**
     * @OA\Property(
     *      title="company name Id",
     *      description="company name  id",
     *      example="Schneider and sons"
     * )
     *
     * @var string
     */
    public $type;

    /**
     * @OA\Property(
     *      title="company name Id",
     *      description="company name  id",
     *      example="Schneider and sons"
     * )
     *
     * @var string
     */
    public $registration_company_number;

    /**
     * @OA\Property(
     *      title="phone",
     *      description="phone",
     *      example="08038893893"
     * )
     *
     * @var string
     */
    public $phone;

    /**
     * @OA\Property(
     *      title="company name Id",
     *      description="company name  id",
     *      example="schneider@rnei.xom"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="company name",
     *      description="company name ",
     *      example="logobase64"
     * )
     *
     * @var string
     */
    public $logo;

    /**
     * @OA\Property(
     *      title="address",
     *      description="address",
     *      example="Schneider and sons street"
     * )
     *
     * @var string
     */
    public $address;

    /**
     * @OA\Property(
     *      title="Country Id",
     *      description="Country id",
     *      example="39403940349"
     * )
     *
     * @var string
     */
    public $country_id;

    /**
     * @OA\Property(
     *      title="state Id",
     *      description="state id",
     *      example="39403940349"
     * )
     *
     * @var string
     */
    public $state_id;

    /**
     * @OA\Property(
     *      title="city Id",
     *      description="city id",
     *      example="39403940349"
     * )
     *
     * @var string
     */
    public $city_id;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
            'company_name' => 'required|string',
            // 'type' => 'nullable|string|In:Business,Limited Company,Incorprated Trustee',
            // 'registration_company_number' => 'nullabe|string',
            'phone' => 'required|string',
            'email' => 'required|string',
            'logo' => 'required|string',
            'address' => 'required|string',
            'country_id' => 'nullable|string|max:255',
            'state_id' => 'nullable|string|max:255',
            'city_id' => 'nullable|string|max:255',
        ];
    }
}
