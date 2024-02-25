<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        static $counter = 1;

        // $file = \Illuminate\Http\UploadedFile::fake()->create('test.pdf')->store('public/documents');
        return [
            'title' => $this->faker->randomElement(['Affidavit Request', 'Notary request']),
            'files_count' => $counter++,
            // 'files' => [$file],
            'user_id' => User::all()->random()->id,
        ];
    }
}
