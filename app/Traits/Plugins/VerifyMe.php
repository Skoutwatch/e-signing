<?php

namespace App\Traits\Plugins;

use Carbon\Carbon;
use Illuminate\Support\Str;

class VerifyMe
{
    public function verifyUser($user, $request)
    {
        $request_body = [
            'firstname' => config('verifyme.api_mode') == 'dev' ? 'John' : $user->first_name,
            'lastname' => config('verifyme.api_mode') == 'dev' ? 'Doe' : $user->last_name,
            'dob' => config('verifyme.api_mode') == 'dev' ? '04-04-1944' : Carbon::parse($request['dob'])->format('d-m-Y'),
        ];

        $bvn = config('verifyme.api_mode') == 'dev' ? 10000000001 : $request['value'];

        $res = $this->sendRequest('https://vapi.verifyme.ng/v1/verifications/identities/bvn/'.$bvn, 'POST', json_encode($request_body));

        return $res;
    }

    public function verifyUserVNIN($user, $request)
    {
        $request_body = [
            'firstname' => config('verifyme.api_mode') == 'dev' ? 'John' : $user->first_name,
            'lastname' => config('verifyme.api_mode') == 'dev' ? 'Doe' : $user->last_name,
            'dob' => config('verifyme.api_mode') == 'dev' ? '04-04-1944' : Carbon::parse($request['dob'])->format('d-m-Y'),
        ];

        $nin = config('verifyme.api_mode') == 'dev' ? 'JZ426633988976CH' : $request['value'];

        return $this->sendRequest('https://vapi.verifyme.ng/v1/verifications/identities/virtual-nin/'.$nin, 'POST', json_encode($request_body));
    }

    public function verifyUserNIN($user, $request)
    {
        $request_body = [
            'firstname' => config('verifyme.api_mode') == 'dev' ? 'John' : $user->first_name,
            'lastname' => config('verifyme.api_mode') == 'dev' ? 'Doe' : $user->last_name,
            'dob' => config('verifyme.api_mode') == 'dev' ? '04-04-1944' : Carbon::parse($request['dob'])->format('d-m-Y'),
        ];

        $nin = config('verifyme.api_mode') == 'dev' ? 10000000001 : $request['value'];

        return $this->sendRequest('https://vapi.verifyme.ng/v1/verifications/identities/nin/'.$nin, 'POST', json_encode($request_body));
    }

    public function verifyCompany($company)
    {
        $request_body = [
            'type' => config('verifyme.api_mode') == 'dev' ? Str::slug($company['type'], '_') : Str::slug($company['type'], '_'),
            'rcNumber' => config('verifyme.api_mode') == 'dev' ? 11000011 : ((int) $company['registration_company_number']),
        ];

        return $this->sendRequest('https://vapi.verifyme.ng/v1/verifications/identities/cac', 'POST', json_encode($request_body));
    }

    public function verifyUserDriverLicense($user, $request)
    {
        $request_body = [
            'firstname' => config('verifyme.api_mode') == 'dev' ? 'John' : $user->first_name,
            'lastname' => config('verifyme.api_mode') == 'dev' ? 'Doe' : $user->last_name,
            'phone' => config('verifyme.api_mode') == 'dev' ? '080000000000' : $user->phone,
            'dob' => config('verifyme.api_mode') == 'dev' ? '04-04-1944' : Carbon::parse($request['dob'])->format('d-m-Y'),
        ];

        $drivers_license = config('verifyme.api_mode') == 'dev' ? 10000000001 : (int) $request['value'];

        return $this->sendRequest("https://vapi.verifyme.ng/v1/verifications/identities/drivers_license/$drivers_license", 'POST', json_encode($request_body));
    }

    public function sendRequest($url, $requestType, $postfields = [])
    {
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $requestType,
            CURLOPT_POSTFIELDS => $postfields,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer '.config('verifyme.secret_key'),
            ],
        ]);

        $response = curl_exec($curl);

        return json_decode($response);
    }
}
