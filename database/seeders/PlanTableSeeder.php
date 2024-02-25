<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\Plan;
use Illuminate\Database\Seeder;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;

class PlanTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $basic = Plan::create([
            'name' => 'Basic',
            'role' => 'User',
            'description' => 'Basic',
            'amount' => 0,
            'full_description' => 'Get certified true copies of essential documents',
            'periodicity' => 1,
            'periodicity_type' => PeriodicityType::Month,
        ]);

        $pro = Plan::create([
            'name' => 'Pro',
            'role' => 'User',
            'description' => 'Pro',
            'amount' => 9900,
            'full_description' => 'Get certified true copies of essential documents',
            'periodicity' => 1,
            'periodicity_type' => PeriodicityType::Month,
        ]);

        $business = Plan::create([
            'name' => 'Business',
            'role' => 'User',
            'description' => 'Business',
            'full_description' => 'Get certified true copies of essential documents',
            'amount' => 19900,
            'periodicity' => 1,
            'periodicity_type' => PeriodicityType::Month,
        ]);

        $custom = Plan::create([
            'name' => 'Custom',
            'role' => 'User',
            'description' => 'Custom',
            'full_description' => 'Get certified true copies of essential documents',
            'amount' => 0,
            'periodicity' => 1,
            'periodicity_type' => PeriodicityType::Month,
        ]);

        $notaryPack1 = Plan::create([
            'name' => 'Notary Seal Packs',
            'role' => 'User',
            'type' => 'Packs',
            'description' => 'Seal Packs',
            'discount_mode' => 'percentage',
            'discount_applied' => true,
            'minimum_discount_unit' => 1,
            'maximum_discount_unit' => 10,
            'discount_percentage' => 0,
            'amount' => 0,
            'grace_days' => 0,
            'periodicity' => 1,
            'periodicity_type' => PeriodicityType::Year,
        ]);

        $notaryPack2 = Plan::create([
            'name' => 'Notary Seal Packs',
            'role' => 'User',
            'type' => 'Packs',
            'description' => 'Seal Packs',
            'discount_mode' => 'percentage',
            'discount_applied' => true,
            'minimum_discount_unit' => 11,
            'maximum_discount_unit' => 20,
            'discount_percentage' => 10,
            'amount' => 0,
            'grace_days' => 0,
            'periodicity' => 1,
            'periodicity_type' => PeriodicityType::Year,
        ]);

        $notaryPack3 = Plan::create([
            'name' => 'Notary Seal Packs',
            'role' => 'User',
            'type' => 'Packs',
            'description' => 'Seal Packs',
            'discount_mode' => 'percentage',
            'discount_applied' => true,
            'minimum_discount_unit' => 21,
            'maximum_discount_unit' => 40,
            'discount_percentage' => 15,
            'amount' => 0,
            'grace_days' => 0,
            'periodicity' => 1,
            'periodicity_type' => PeriodicityType::Year,
        ]);

        $notaryPack4 = Plan::create([
            'name' => 'Notary Seal Packs',
            'role' => 'User',
            'type' => 'Packs',
            'description' => 'Seal Packs',
            'discount_mode' => 'percentage',
            'discount_applied' => true,
            'minimum_discount_unit' => 41,
            'maximum_discount_unit' => 60,
            'discount_percentage' => 25,
            'amount' => 0,
            'grace_days' => 0,
            'periodicity' => 1,
            'periodicity_type' => PeriodicityType::Year,
        ]);

        $notaryPack5 = Plan::create([
            'name' => 'Notary Seal Packs',
            'role' => 'User',
            'type' => 'Packs',
            'description' => 'Seal Packs',
            'discount_mode' => 'percentage',
            'discount_applied' => true,
            'minimum_discount_unit' => 61,
            'maximum_discount_unit' => 80,
            'discount_percentage' => 35,
            'amount' => 0,
            'grace_days' => 0,
            'periodicity' => 1,
            'periodicity_type' => PeriodicityType::Year,
        ]);

        $notaryPack6 = Plan::create([
            'name' => 'Notary Seal Packs',
            'role' => 'User',
            'type' => 'Packs',
            'description' => 'Seal Packs',
            'discount_mode' => 'percentage',
            'discount_applied' => true,
            'minimum_discount_unit' => 81,
            'maximum_discount_unit' => 100,
            'discount_percentage' => 50,
            'amount' => 0,
            'grace_days' => 0,
            'periodicity' => 1,
            'periodicity_type' => PeriodicityType::Year,
        ]);

        $users = Feature::create([
            'consumable' => true,
            'model_type' => 'TeamUser',
            'name' => 'Number of Users',
        ]);

        $envelops = Feature::create([
            'consumable' => true,
            'model_type' => 'Document',
            'name' => 'Number of Envelops',
        ]);

        $trail = Feature::create([
            'consumable' => false,
            'model_type' => null,
            'name' => 'Access to audit trail',
        ]);

        $seal = Feature::create([
            'consumable' => false,
            'model_type' => null,
            'name' => 'Digitise, Signature, Stamp and Seal',
        ]);

        $branding = Feature::create([
            'consumable' => false,
            'model_type' => 'Company',
            'name' => 'Personal Branding',
        ]);

        $sms = Feature::create([
            'consumable' => false,
            'model_type' => null,
            'name' => 'SMS Notifications',
        ]);

        $usersFeature = Feature::where('name', 'Number of Users')->first();
        $envelopsFeature = Feature::where('name', 'Number of Envelops')->first();
        $trailFeature = Feature::where('name', 'Access to audit trail')->first();
        $sealFeature = Feature::where('name', 'Digitise, Signature, Stamp and Seal')->first();
        $brandingFeature = Feature::where('name', 'Personal Branding')->first();
        $smsFeature = Feature::where('name', 'SMS Notifications')->first();

        $basic->features()->attach($usersFeature, ['charges' => 1]);
        $pro->features()->attach($usersFeature, ['charges' => 3]);
        $business->features()->attach($usersFeature, ['charges' => 10]);

        $basic->features()->attach($envelopsFeature, ['charges' => 10]);
        $pro->features()->attach($envelopsFeature, ['charges' => 50]);
        $business->features()->attach($envelopsFeature, ['charges' => 100]);

        $basic->features()->attach($trailFeature);
        $pro->features()->attach($trailFeature);
        $business->features()->attach($trailFeature);

        $basic->features()->attach($sealFeature);
        $pro->features()->attach($sealFeature);
        $business->features()->attach($sealFeature);

        $business->features()->attach($brandingFeature);
        $business->features()->attach($smsFeature);
    }
}
