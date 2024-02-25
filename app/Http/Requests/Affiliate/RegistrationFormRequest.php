<?php

namespace App\Http\Requests\Affiliate;

use App\Enums\AffiliatePartnerType;
use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *      title="Address Create Form Request Fields",
 *      description="Address Create Form request body data",
 *      type="object",
 *     required={"first_name", "last_name", "email", "phone", "company", "job_title", "more_info", "partner_type"}
 * )
 */
class RegistrationFormRequest extends FormRequest
{
    /**
     * @OA\Property(
     *      title="First name",
     *      description="First name of registrant",
     *      example="John"
     * )
     */
    public string $first_name;

    /**
     * @OA\Property(
     *      title="Last name",
     *      description="Last name of user",
     *      example="Doe"
     * )
     */
    public string $last_name;

    /**
     * @OA\Property(
     *      title="Email",
     *      description="Email address",
     *      example="john.doe@example.com",
     *      format="email"
     * )
     */
    public ?string $email;

    /**
     * @OA\Property(
     *      title="Phone",
     *      description="Phone number",
     *      example="0803123457068"
     * )
     */
    public string $phone;

    /**
     * @OA\Property(
     *      title="Company",
     *      description="Name of company",
     *      example="ToNote Tech Ltd",
     * )
     */
    public string $company;

    /**
     * @OA\Property(
     *      title="Job title",
     *      description="The job title of the person applying",
     *      example="Sales officer"
     * )
     */
    public string $job_title;

    /**
     * @OA\Property(
     *      title="More info",
     *      description="The 'Tell us more' field data",
     *      example="Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua."
     * )
     */
    public string $more_info;

    /**
     * @OA\Property(
     *      title="Partnership type",
     *      description="How the applicant wants to partner with us. Check the api/v1/affiliates/partner-types for the options",
     *      example="0"
     * )
     */
    public int $partner_type;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|numeric',
            'company' => 'required|string|max:60',
            'job_title' => 'required|string|max:30',
            'more_info' => 'required|string|max:255',
            'partner_type' => 'required|integer|in:'.implode(',', AffiliatePartnerType::getValues()),
        ];
    }
}
