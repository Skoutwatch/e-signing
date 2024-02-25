<?php

namespace App\Models;

use App\Http\Resources\SignaturePrint\AppendPrintCollection;
use App\Http\Resources\SignaturePrint\AppendPrintResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppendPrint extends Model
{
    use HasFactory, HasUuids;

    public $oneItem = AppendPrintResource::class;

    public $allItems = AppendPrintCollection::class;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tools()
    {
        return $this->hasMany(DocumentResourceTool::class, 'append_print_id');
    }
}
