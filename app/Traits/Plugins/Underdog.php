<?php

namespace App\Traits\Plugins;

use Illuminate\Support\Str;

class Underdog
{
    public function __construct(public $url = null, public $projectId = null)
    {
        $this->url = config('underdog.url');
        $this->projectId = config('underdog.project_id');
    }

    public function createNfts($document)
    {
        $content = [
            'name' => Str::limit($document->title, 20, '...'),
            'symbol' => "SW",
            'description' => "This process will mint the document attributes of " . $document->title . " " . $document->id,
            'image' => "https://skoutwatch.com/img/2asset%203%201.png",
            'externalUrl' => $document->completedDocument->file_url,
            'receiverAddress' => config('underdog.receiver_address'),
            'upsert' => true,
            'delegated' => true,
            // 'attributes' => [
            //     // ["traitType" => "ID", "value" => $document->id],
            //     // ["traitType" => 'Participants', "value" => $document->participants->count()],
            //     // ["traitType" => "status", "value" => $document->status],
            //     // ["traitType" => "status", "value" => (object)($document)],
            // ]
        ];

        return $response = $this->sendRequest("{$this->url}/v2/projects/{$this->projectId}/nfts", 'POST', json_encode($content));
    }

    public function searchNfts()
    {
        return $this->sendRequest("{$this->url}/v2/projects/{$this->projectId}/nfts/search?page=1&limit=10", 'GET', []);
    }

    public function getAllNfts($transaction)
    {
        return $this->sendRequest("{$this->url}/v2/projects/{$this->projectId}/nfts?page=1&limit=10", 'GET', []);
    }

    public function getNftsById($id)
    {
        return $this->sendRequest("{$this->url}/v2/projects/{$this->projectId}/nfts/$id", 'GET', []);
    }

    public function updateNftsById($user, $id)
    {
        $content = [
            'description' => $user->description,
            'image' => $user->image,
            'externalUrl' => 'N/A',
            'receiverAddress' => config('underdog.receiver_address'),
            'attributes' => []
        ];

        return $this->sendRequest("{$this->url}/v2/projects/{$this->projectId}/nfts/$id", 'PUT', json_encode($content));

    }

    public function updatePartialNftsById($user, $id)
    {
        $content = [
            'description' => $user->description,
            'image' => $user->image,
            'externalUrl' => 'N/A',
            'receiverAddress' => config('underdog.receiver_address'),
            'attributes' => []
        ];

        return $this->sendRequest("{$this->url}/v2/projects/{$this->projectId}/nfts/$id", 'PATCH', json_encode($content));

    }


    private function sendRequest($url, $requestType, $postfields = [])
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
                'Authorization: Bearer '.config('underdog.key'),
            ],
        ]);

        $response = curl_exec($curl);

        return json_decode($response);
    }
}