<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CountriesTableSeeder::class,
            PaymentGatewayListTableSeeder::class,
            PricingPlanSeeder::class,
            FeatureTableSeeder::class,
            BankTableSeeder::class,
            DocumentTemplateSeeder::class,
            PlanTrialTableSeeder::class,
            PermissionTableSeeder::class,
            ComplianceQuestionsTableSeeder::class,
        ]);
    }
}
