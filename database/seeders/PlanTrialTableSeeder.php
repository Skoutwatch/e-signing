<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Plan;
use App\Models\PlanBenefit;
use Illuminate\Database\Seeder;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;

class PlanTrialTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $trialBasic = Plan::create([
            'icon' => 'https://staging-tonote-frontend.s3.eu-west-3.amazonaws.com/fa6-solid_user.png',
            'name' => 'Basic',
            'role' => 'User',
            'description' => 'Basic',
            'trial' => true,
            'next_suggested_plan' => 'Pro',
            'amount' => 0,
            'full_description' => 'Get certified true copies of essential documents',
            'periodicity' => 2,
            'periodicity_type' => PeriodicityType::Week,
        ]);

        $trialPro = Plan::create([
            'icon' => 'https://staging-tonote-frontend.s3.eu-west-3.amazonaws.com/fa6-solid_crownking.png',
            'name' => 'Pro',
            'role' => 'User',
            'description' => 'Pro',
            'next_suggested_plan' => 'Pro',
            'amount' => 9900,
            'trial' => true,
            'full_description' => 'Get certified true copies of essential documents',
            'periodicity' => 2,
            'periodicity_type' => PeriodicityType::Week,
        ]);

        $trialBusiness = Plan::create([
            'icon' => 'https://staging-tonote-frontend.s3.eu-west-3.amazonaws.com/fa6-solid_briefcase.png',
            'name' => 'Business',
            'role' => 'User',
            'description' => 'Business',
            'next_suggested_plan' => 'Pro',
            'trial' => true,
            'full_description' => 'Get certified true copies of essential documents',
            'amount' => 19900,
            'periodicity' => 2,
            'periodicity_type' => PeriodicityType::Week,
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBasic->id,
            'description' => 'No subscription fee',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBasic->id,
            'description' => 'Digitise and store your secure e - signature',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBasic->id,
            'description' => 'Get affidavits and notarise documents online in minutes! No templates required',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBasic->id,
            'description' => 'Securely sign and share an unlimited number of notarised documents with third parties',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBasic->id,
            'description' => 'Securely sign and share 10 documents monthly at no cost',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBasic->id,
            'description' => 'No more paper, printing, or transportation',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBasic->id,
            'description' => 'Audit trail for documentation management',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBasic->id,
            'description' => 'Access to online tutorials on how to use the platform',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBasic->id,
            'description' => 'Access to customer support via email and chat',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBasic->id,
            'description' => '24 / 7 web access',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialPro->id,
            'description' => 'Everything on Basic Plan',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialPro->id,
            'description' => 'Bank Grade Security',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialPro->id,
            'description' => 'E-sign and share up to 40 documents per team member monthly.',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialPro->id,
            'description' => 'Collaboration with up to 10 team members',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialPro->id,
            'description' => 'Set permissions for signers',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialPro->id,
            'description' => 'Sign documents collaboratively',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialPro->id,
            'description' => 'Automate approvals and sign offs',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialPro->id,
            'description' => 'Enable seamless documentation management processes',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialPro->id,
            'description' => 'Quicker transaction closings',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialPro->id,
            'description' => '24 / 7 web access',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBusiness->id,
            'description' => 'Everything on Basic Plan ',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBusiness->id,
            'description' => 'Securely sign and share up to 100 documents per team member monthly',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBusiness->id,
            'description' => 'Collaborate with up to 100 team members monthly',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBusiness->id,
            'description' => 'Seamless documentation management processes between your business and Clients',
        ]);

        PlanBenefit::create([
            'plan_id' => $trialBusiness->id,
            'description' => 'Access to dedicated customer success manager',
        ]);

        $usersFeature = Feature::where('name', 'Number of Users')->first();
        $envelopsFeature = Feature::where('name', 'Number of Envelops')->first();
        $trailFeature = Feature::where('name', 'Access to audit trail')->first();
        $sealFeature = Feature::where('name', 'Digitise, Signature, Stamp and Seal')->first();
        $brandingFeature = Feature::where('name', 'Personal Branding')->first();
        $smsFeature = Feature::where('name', 'SMS Notifications')->first();

        $trialBasic->features()->attach($usersFeature, ['charges' => 1]);
        $trialPro->features()->attach($usersFeature, ['charges' => 3]);
        $trialBusiness->features()->attach($usersFeature, ['charges' => 10]);

        $trialBasic->features()->attach($envelopsFeature, ['charges' => 10]);
        $trialPro->features()->attach($envelopsFeature, ['charges' => 50]);
        $trialBusiness->features()->attach($envelopsFeature, ['charges' => 100]);

        $trialBasic->features()->attach($trailFeature);
        $trialPro->features()->attach($trailFeature);
        $trialBusiness->features()->attach($trailFeature);

        $trialBasic->features()->attach($sealFeature);
        $trialPro->features()->attach($sealFeature);
        $trialBusiness->features()->attach($sealFeature);

        $trialBusiness->features()->attach($brandingFeature);
        $trialBusiness->features()->attach($smsFeature);
    }
}
