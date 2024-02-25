<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\DocumentTemplate;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ScheduleSession>
 */
class ScheduleSessionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'entry_point' => $this->faker->randomElement(['Docs', 'Notary', 'Video', 'Affidavit', 'CFO']),
            'description' => $this->faker->paragraph,
            'request_type' => $this->faker->randomElement(['Document', 'Upload', 'Template', 'Custom']),
            'session_type' => $this->faker->randomElement(['notary_session', 'affidavit', 'video']),
            'files' => $this->faker->randomElement([
                [
                    'title' => $this->faker->word,
                    'entry_point' => $this->faker->randomElement(['Docs', 'Notary', 'Video', 'Affidavit', 'CFO']),
                    'file' => base64_encode($this->faker->text), // Generate base64-encoded data
                ],
            ]),
            'parent_id' => function () {
                // Ensure a valid parent_id exists
                return Document::factory()->create()->id;
            },
            'document_id' => function () {
                // Ensure a valid document_id exists
                return Document::factory()->create()->id;
            },
            'notary_id' => function () {
                // Ensure a valid notary_id exists (if applicable)
                return User::factory()->create()->id;
            },
            'document_template_id' => function () {
                // Ensure a valid document_template_id exists
                return DocumentTemplate::factory()->create()->id;
            },
            'delivery_channel' => $this->faker->randomElement(['Email', 'Address', 'Collection']),
            'delivery_address' => $this->faker->address,
            'platform_initiated' => $this->faker->randomElement(['Web', 'Mobile']),
            'actor_type' => $this->faker->randomElement(['User', 'Team']),
            'set_reminder_in_minutes' => $this->faker->numberBetween(15, 60),
            'immediate' => $this->faker->boolean,
            'date' => $this->faker->date(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time('H:i:s', $this->faker->dateTimeBetween('-1 hour', '+1 hour')->format('H:i:s')),
            'recipient_name' => $this->faker->name,
            'recipient_email' => $this->faker->email,
            'recipient_contact' => $this->faker->phoneNumber,
            'has_monetary_value' => $this->faker->boolean,
            'participants' => [
                [
                    'first_name' => $this->faker->firstName,
                    'last_name' => $this->faker->lastName,
                    'email' => $this->faker->unique()->safeEmail,
                    'phone' => $this->faker->phoneNumber,
                    'role' => $this->faker->randomElement(['Signer', 'Viewer']),
                    'entry_point' => $this->faker->randomElement(['Docs', 'Notary', 'Video', 'Affidavit', 'CFO']),
                ],
            ],
        ];
    }
}
