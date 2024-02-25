<?php

namespace Database\Factories;

use App\Models\Document;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DocumentParticipant>
 */
class DocumentParticipantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'document_id' => Document::all()->random()->id,
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'phone' => $this->faker->e164PhoneNumber(),
            'email' => $this->faker->safeEmail(),
            'role' => $this->faker->randomElement(['Signer', 'Viewer']),
        ];
    }
}
