<?php

namespace App\Models;

use App\Filament\Resources\RoleResource;
use Filament\Models\Contracts\HasResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasUuids;
}
