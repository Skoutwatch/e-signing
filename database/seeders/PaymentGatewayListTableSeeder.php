<?php

namespace Database\Seeders;

use App\Models\Location\Country;
use App\Models\PaymentGateway;
use App\Models\PaymentGatewayList;
use Illuminate\Database\Seeder;

class PaymentGatewayListTableSeeder extends Seeder
{
    public function run()
    {
        $paystack = PaymentGatewayList::create([
            'name' => 'Paystack',
            'file' => 'https://play-lh.googleusercontent.com/qgZosJ7Reiz0sWMV3miM-2AXxzK7GUOPczJMau30wzF7GsTK4bvJLROEUQdgQj3Gs_c=w240-h480-rw',
        ]);

        $flutterwave = PaymentGatewayList::create([
            'name' => 'Flutterwave',
            'file' => 'https://cdn.filestackcontent.com/OITnhSPCSzOuiVvwnH7r',
        ]);

        $credo = PaymentGatewayList::create([
            'name' => 'Credo',
            'file' => 'https://media-exp1.licdn.com/dms/image/C4D0BAQEeI_YGhFbTug/company-logo_200_200/0/1602399117162?e=1672272000&v=beta&t=JZJyjgBEPAQgwITzo7fzQxnCclzj07-lYlyNU_rGJfk',
        ]);

        PaymentGateway::create([
            'payment_gateway_list_id' => $paystack->id,
            'country_id' => Country::where('name', 'Nigeria')->first()->id,
        ]);

        PaymentGateway::create([
            'payment_gateway_list_id' => $flutterwave->id,
            'country_id' => Country::where('name', 'Nigeria')->first()->id,
        ]);

        PaymentGateway::create([
            'payment_gateway_list_id' => $credo->id,
            'country_id' => Country::where('name', 'Nigeria')->first()->id,
            'active' => false,
        ]);
    }
}
