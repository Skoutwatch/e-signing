<?php

namespace App\Http\Controllers\Api\V1\Signlink;

use App\Http\Controllers\Controller;
use App\Models\Document;

class SignlinkShareEmailLinkController extends Controller
{
    public function show($id)
    {
        $document = Document::find($id);
    }
}
