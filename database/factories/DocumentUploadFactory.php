<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DocumentUpload>
 */
class DocumentUploadFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $file = \Illuminate\Http\UploadedFile::fake()->create('test.pdf')->store('pdfs');

        return [
            'document_id' => Document::all()->random()->id,
            'file_url' => $file,
            'user_id' => User::all()->random()->id,
            'status' => 'New',
        ];
    }
}
