<?php

namespace Database\Seeders;

use App\Models\ComplianceQuestion;
use Illuminate\Database\Seeder;

class ComplianceQuestionsTableSeeder extends Seeder
{
    public function run()
    {
        ComplianceQuestion::create([
            'question' => 'Before we commence, could you kindly hold your ID up to the screen so I can once again verify your identity?',
            'default' => true,
        ]);

        ComplianceQuestion::create([
            'question' => 'Today\'s session is being recorded for audit purposes. Is that okay with you?',
            'default' => true,
        ]);

        ComplianceQuestion::create([
            'question' => 'Do you understand the content of the document you are about to Notarise/Sign?',
            'default' => true,
        ]);

        ComplianceQuestion::create([
            'question' => 'Are you participating in today\'s Notarization of your own free will?',
            'default' => true,
        ]);

        ComplianceQuestion::create([
            'question' => 'Do you agree to use an electronic signature to sign the document?',
            'default' => true,
        ]);

        ComplianceQuestion::create([
            'question' => 'Do you understand & agree that by signing the document you legally bind yourself to the terms of the document?',
            'default' => true,
        ]);
    }
}
