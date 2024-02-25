<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AppendPrint>
 */
class AppendPrintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['Initial', 'Signature', 'NotaryStamp', 'NotaryTraditionalSeal', 'NotaryDigitalSeal', 'CompanyStamp', 'CompanySeal', 'Photograph', 'LeftThumbFinger', 'LeftPointerFinger', 'LeftMiddleFinger', 'LeftRingFinger', 'LeftPinkyFinger', 'LeftPinkyFinger', 'RightThumbFinger', 'RightPointerFinger', 'RightMiddleFinger', 'RightRingFinger', 'RightPinkyFinger', 'Text']),
            'category' => $this->faker->randomElement(['Draw', 'Type', 'Upload']),
            'value' => $this->faker->word,
            'user_id' => User::all()->random()->id,
        ];
    }
}
