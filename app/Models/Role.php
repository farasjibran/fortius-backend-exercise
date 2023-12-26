<?php

namespace App\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    public const SUPERADMIN = 'SUPER_ADMIN';
    public const USER = 'USER';
}
