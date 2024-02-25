<?php

namespace Database\Seeders;

use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\State;
use App\Models\Location\Timezone;
use App\Models\Location\Translation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Country::truncate();
        // State::truncate();
        // City::truncate();
        // Timezone::truncate();

        $json = File::get('database/json/countries_states_cities.json');
        $countries = json_decode($json);

        foreach ($countries as $key => $country) {
            if ($country->name == 'Nigeria') {
                $countryProperty = Country::create([
                    'name' => $country->name,
                    'iso3' => $country->iso3,
                    'iso2' => $country->iso2,
                    'numeric_code' => $country->numeric_code,
                    'phone_code' => $country->phone_code,
                    'capital' => $country->capital,
                    'currency' => $country->currency,
                    'currency_symbol' => $country->currency_symbol,
                    'tld' => $country->tld,
                    'native' => $country->native,
                    'region' => $country->region,
                    'latitude' => $country->latitude,
                    'longitude' => $country->longitude,
                    'emoji' => $country->emoji,
                    'emojiU' => $country->emojiU,
                ]);

                foreach ($country->timezones as $key => $timezone) {
                    Timezone::create([
                        'zoneName' => $timezone->zoneName,
                        'gmtOffset' => $timezone->gmtOffset,
                        'gmtOffsetName' => $timezone->gmtOffsetName,
                        'abbreviation' => $timezone->abbreviation,
                        'tzName' => $timezone->tzName,
                        'country_id' => $countryProperty->id,
                    ]);
                }

                foreach ($country->translations as $key => $translations) {
                    Translation::create([
                        'code' => $key,
                        'translation' => $translations,
                        'country_id' => $countryProperty->id,
                    ]);
                }

                foreach ($country->states as $key => $state) {
                    $stateProperty = State::create([
                        'name' => $state->name,
                        'state_code' => $state->state_code,
                        'latitude' => $state->latitude,
                        'longitude' => $state->longitude,
                        'type' => $state->type,
                        'country_id' => $countryProperty->id,
                    ]);

                    foreach ($state->cities as $key => $city) {
                        City::create([
                            'name' => $city->name,
                            'latitude' => $city->latitude,
                            'longitude' => $city->longitude,
                            'country_id' => $countryProperty->id,
                            'state_id' => $stateProperty->id,
                        ]);
                    }
                }
            }
        }
    }
}
