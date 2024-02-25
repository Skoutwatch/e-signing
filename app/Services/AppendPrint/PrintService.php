<?php

namespace App\Services\AppendPrint;

use App\Models\AppendPrint;
use App\Services\Document\DocumentConversionService;
use App\Traits\Api\ApiResponder;

class PrintService
{
    use ApiResponder;

    public function findIfSignatureExist($user, $request)
    {
        if ($request['type'] == 'Signature'
            || $request['type'] == 'NotaryStamp'
            || $request['type'] == 'NotaryDigitalSeal'
            || $request['type'] == 'NotaryTraditionalSeal'
            || $request['type'] == 'CompanyStamp'
            || $request['type'] == 'Initial'
            || $request['type'] == 'CompanySeal') {
            $print = AppendPrint::where('user_id', $user->id)
                ->where('type', $request['type'])
                ->where('category', $request['category'])
                ->first();

            return $print ? $this->updatePrint($print, $request) : $this->storeNewPrint($user, $request);
        } elseif ($request['type'] == 'Photograph' && $request['category'] == 'Upload') {
            $print = AppendPrint::where('user_id', $user->id)->where('type', $request['type'])->get();

            ($print->count() > 2) ?? throw new \ErrorException('You have reached the maximum limit of photograph uploads');

            return $this->storeNewPrint($user, $request);
        } elseif ($request['type'] == 'Camera' && $request['category'] == 'Upload') {
            $print = AppendPrint::where('user_id', $user->id)->where('type', $request['type'])->get();

            ($print->count() > 1) ?? throw new \ErrorException('You have reached the maximum limit of photograph uploads');

            return $this->storeNewPrint($user, $request);
        } else {
            $this->storeNewPrint($user, $request);
        }
    }

    public function storeNewPrint($user, $request)
    {
        $value = $this->fileStorage($request['file'], $user);

        $storeValue = $this->uploadPrint($value['storage']);

        $user->prints()->create(
            array_merge(
                $request->except('file'),
                ['file' => $storeValue]
            )
        );
    }

    public function updatePrint($print, $request)
    {
        $value = $this->fileStorage($request['file'], $print->user);

        $storeValue = $this->uploadPrint($value['storage']);

        $print->update(
            array_merge(
                $request->except('file'),
                ['file' => $storeValue]
            )
        );
    }

    public function allowOnlyNotaryToStoreTheirStampAndSeal($user, $request)
    {
        return $user->role != 'Notary' && $request['type'] == 'Stamp' && $request['type'] == 'Seal'
                ? $this->errorResponse('You are not autorized to access this activity', 409)
                : null;
    }

    public function fileStorage($file, $print)
    {
        return (new DocumentConversionService())->fileStorage($file, $print);
    }

    public function uploadPrint($value)
    {
        return (new DocumentConversionService())->storeImage($value);
    }

    public function createPrintTextFromResourceTool($data)
    {
        return AppendPrint::create([
            'user_id' => $data['user_id'],
            'type' => 'Text',
            'category' => 'Type',
            'value' => $data['value'] ? $data['value'] : null,
        ]);
    }

    public function updatePrintTextFromResourceTool($data)
    {
        $print = AppendPrint::find($data['append_print_id']);

        $print->update([
            'value' => $data['value'] ? $data['value'] : null,
        ]);

        return AppendPrint::find($data['append_print_id']);
    }
}
