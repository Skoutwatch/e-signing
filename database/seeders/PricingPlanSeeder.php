<?php

namespace Database\Seeders;

use App\Models\ServicePlan;
use Illuminate\Database\Seeder;

class PricingPlanSeeder extends Seeder
{
    public function run()
    {
        ServicePlan::create([
            'name' => 'Notary',
            'apply_additional_price' => true,
            'additional_unit_price' => 3500,
            'additional_price_measured_unit' => 1,
            'notary_fee_cost' => 5000,
            'regulatory_fee_cost' => 500,
            'other_partners_cost' => 500,
            'tonote_service_cost' => 2000,
            'agora_cost' => 300,
            'aws_cost' => 101,
            'email_cost' => 3,
            'customer_support_cost' => 5,
            'verifyme_cost' => 250,
            'payment_processing_cost' => 229.89,
            'total' => 8888.89,
        ]);

        ServicePlan::create([
            'name' => 'Affidavits',
            'notary_fee_cost' => 2000,
            'regulatory_fee_cost' => 250,
            'other_partners_cost' => 250,
            'tonote_service_cost' => 1500,
            'agora_cost' => 300,
            'aws_cost' => 72,
            'email_cost' => 2,
            'customer_support_cost' => 4,
            'verifyme_cost' => 250,
            'payment_processing_cost' => 169.42,
            'total' => 4797.42,
        ]);
    }
}
