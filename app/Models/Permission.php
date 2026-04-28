<?php

namespace App\Models;

use App\Filament\Resources\PermissionResource;
use Filament\Models\Contracts\HasResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    use HasUuids;
}
