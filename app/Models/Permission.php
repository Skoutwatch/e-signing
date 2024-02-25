<?php

namespace App\Models;

use App\Http\Resources\Permission\PermissionCollection;
use App\Http\Resources\Permission\PermissionResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    use HasFactory, HasUuids;

    public $guarded = [];

    public $oneItem = PermissionResource::class;

    public $allItems = PermissionCollection::class;

    public static function defaultPermissions()
    {
        return [
            'create_users',
            'edit_users',
            'show_users',
            'delete_users',
        ];
    }
}
