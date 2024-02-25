<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
/**
 * @OA\Schema(
 *      title="User Update Form Request Fields",
 *      description="User Update request body data",
 *      type="object",
 *      required={"first_name"}
 * )
 */
class AuthUpdateFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="First Name",
     *      description="first name of the user",
     *      example="Bronx"
     * )
     *
     * @var string
     */
    public $first_name;

    /**
     * @OA\Property(
     *      title="Last Name",
     *      description="Last name of the user",
     *      example="Bronx"
     * )
     *
     * @var string
     */
    public $last_name;

    /**
     * @OA\Property(
     *      title="Phone",
     *      description="phone of the user",
     *      example="+23400233300"
     * )
     *
     * @var string
     */
    public $phone;

    /**
     * @OA\Property(
     *      title="gender",
     *      description="gender of the user",
     *      example="m/f/o"
     * )
     *
     * @var string
     */
    public $gender;

    /**
     * @OA\Property(
     *      title="Address",
     *      description="Address of the user",
     *      example="No 5 Jesus Street"
     * )
     *
     * @var string
     */
    public $address;

    /**
     * @OA\Property(
     *      title="dob",
     *      description="date of birth",
     *      example="1994-49-33"
     * )
     *
     * @var string
     */
    public $dob;

    /**
     * @OA\Property(
     *      title="Notary Commission Number",
     *      description="Notary Commission Number of the notary",
     *      example="39403940349"
     * )
     *
     * @var string
     */
    public $notary_commission_number;

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
     * @OA\Property(
     *      title="is_online",
     *      description="is_online",
     *      example="true/false"
     * )
     *
     * @var string
     */
    public $is_online;

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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'notary_commission_number' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255|In:m,f,o',
            'address' => 'nullable|string|max:255',
            'country_id' => 'nullable|string|max:255',
            'state_id' => 'nullable|string|max:255',
            'city_id' => 'nullable|string|max:255',
            'dob' => 'nullable|date|string',
            'is_online' => 'boolean|nullable',
            'image' => 'nullable|base64image',
        ];
    }
}
