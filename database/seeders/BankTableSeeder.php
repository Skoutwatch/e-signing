<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class BankTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Bank::truncate();

        $json = File::get('database/json/banks.json');
        $countries = json_decode($json);

        foreach ($countries as $key => $value) {
            Bank::firstOrCreate([
                'code' => $value->code,
                'name' => $value->name,
            ]);
        }
    }
}
