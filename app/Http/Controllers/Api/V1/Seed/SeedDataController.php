<?php

namespace App\Http\Controllers\Api\V1\Seed;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Pricing;

class SeedDataController extends Controller
{
    public function index()
    {
        $plan1 = Plan::where('name', 'Basic')->where('trial', false)->first();
        $plan2 = Plan::where('name', 'Pro')->where('trial', false)->first();
        $plan3 = Plan::where('name', 'Business')->where('trial', false)->first();

        $priceNotary1 = Pricing::create([
            'name' => '8k per notary session',
            'description' => '8k per notary session',
            'entry_point' => 'Notary',
            'amount' => 8000,
            'plan_id' => $plan1->id,
            'initial_service_charge' => true,
        ]);

        Pricing::create([
            'name' => '4k per additional seal',
            'description' => '4k per additional seal',
            'entry_point' => 'Notary',
            'amount' => 4000,
            'plan_id' => $plan1->id,
            'initial_service_charge' => false,
        ]);

        Pricing::create([
            'name' => '4k per affidavit',
            'description' => '4k per affidavit',
            'entry_point' => 'Affidavit',
            'amount' => 4000,
            'plan_id' => $plan1->id,
            'initial_service_charge' => true,
        ]);

        Pricing::create([
            'name' => '10k per custom affidavit',
            'description' => '10k per custom affidavit',
            'entry_point' => 'Affidavit',
            'amount' => 10000,
            'plan_id' => $plan1->id,
            'initial_service_charge' => true,
        ]);

        $price2 = Pricing::create([
            'name' => '8k per notary session',
            'description' => '8k per notary session',
            'entry_point' => 'Notary',
            'amount' => 8000,
            'plan_id' => $plan2->id,
        ]);

        Pricing::create([
            'name' => '4k per additional seal',
            'description' => '4k per additional seal',
            'entry_point' => 'Notary',
            'amount' => 4000,
            'plan_id' => $plan2->id,
        ]);

        Pricing::create([
            'name' => '4k per affidavit',
            'description' => '4k per affidavit',
            'entry_point' => 'Affidavit',
            'amount' => 4000,
            'plan_id' => $plan2->id,
        ]);

        Pricing::create([
            'name' => '10k per custom affidavit',
            'description' => '10k per custom affidavit',
            'entry_point' => 'Affidavit',
            'amount' => 10000,
            'plan_id' => $plan2->id,
        ]);

        $price3 = Pricing::create([
            'name' => '6500 per notary session',
            'description' => '6500 per notary session',
            'entry_point' => 'Notary',
            'amount' => 6500,
            'plan_id' => $plan3->id,
        ]);

        Pricing::create([
            'name' => '3500 per additional seal',
            'description' => '3500 per additional seal',
            'entry_point' => 'Notary',
            'amount' => 3500,
            'plan_id' => $plan3->id,
        ]);

        Pricing::create([
            'name' => '3500 per affidavit',
            'description' => '3500 per affidavit',
            'entry_point' => 'Affidavit',
            'amount' => 3500,
            'plan_id' => $plan3->id,
        ]);

        Pricing::create([
            'name' => '9k per custom affidavit',
            'description' => '9k per custom affidavit',
            'entry_point' => 'Affidavit',
            'amount' => 9000,
            'plan_id' => $plan3->id,
        ]);

        Pricing::create([
            'name' => '1k per custom affidavit',
            'description' => '9k per custom affidavit',
            'entry_point' => 'Affidavit',
            'amount' => 1000,
        ]);
    }
}
