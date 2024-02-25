<?php

namespace App\Traits\Plugins;

use ErrorException;

class QoreId
{
    private function getAccessToken()
    {
        $request = [
            'clientId' => (string) config('qoreID.public_key'),
            'secret' => (string) config('qoreID.secret_key'),
        ];

        $res = $this->authenticateRequest('https://api.qoreid.com/token', 'POST', json_encode($request));

        return isset($res->status) ? (throw new ErrorException('Face match error')) : $res->accessToken;
    }

    public function bvnFaceMatch($request)
    {
        $request = [
            'idNumber' => $request['idNumber'],
            'photoBase64' => $request['photoBase64'],
        ];
        $res = $this->sendRequest('https://api.qoreid.com/v1/ng/identities/face-verification/bvn', 'POST', json_encode($request));

        if (isset($res->metadata->match) && $res->metadata->match === true) {
            return $res->bvn;
        } else {
            throw new ErrorException('Face match error');
        }
    }

    public function vninFaceMatch($request)
    {
        $request = [
            'idNumber' => $request['idNumber'],
            'photoBase64' => $request['photoBase64'],
        ];
        $res = $this->sendRequest('https://api.qoreid.com/v1/ng/identities/face-verification/vnin', 'POST', json_encode($request));

        return $res;
    }

    public function ninFaceMatch($request)
    {
        $request = [
            'idNumber' => $request['idNumber'],
            'photoBase64' => $request['photoBase64'],
        ];
        $res = $this->sendRequest('https://api.qoreid.com/v1/ng/identities/face-verification/nin', 'POST', json_encode($request));

        if (isset($res->metadata->match) && $res->metadata->match === true) {
            return $res->nin;
        } else {
            throw new ErrorException('Face match error');
        }
    }

    public function driversLicenseFaceMatch($request)
    {
        $request = [
            'idNumber' => $request['idNumber'],
            'photoBase64' => $request['photoBase64'],
        ];
        $res = $this->sendRequest('https://api.qoreid.com/v1/ng/identities/face-verification/drivers-license', 'POST', json_encode($request));

        if (isset($res->metadata->match) && $res->metadata->match === true) {
            return $res->drivers_license;
        } else {
            throw new ErrorException('Face match error');
        }
    }

    public function verifyCompany($request)
    {
        $request_body = [
            'type' => $request['type'],
            'regNumber' => $request['regNumber'],
        ];

        $res = $this->sendRequest('https://api.qoreid.com/v1/ng/identities/cac-basic', 'POST', json_encode($request_body));

        if (isset($res->summary->cac_check) && $res->summary->cac_check === 'verified') {
            return $res->cac;
        } else {
            throw new ErrorException('Verification failed');
        }
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
                'Authorization: Bearer '.$this->getAccessToken(),
            ],
        ]);

        $response = curl_exec($curl);

        return json_decode($response);
    }

    public function authenticateRequest($url, $requestType, $postfields = [])
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
            ],
        ]);

        $response = curl_exec($curl);

        return json_decode($response);
    }
}
