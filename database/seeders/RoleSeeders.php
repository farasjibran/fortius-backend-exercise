<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = collect(config('services.roles'));

        $roles->map(function ($role) {
            $name = $role;
            $guard_name = 'web';

            Role::query()
                ->updateOrCreate(compact('name'), compact('name', 'guard_name'));
        });
    }
}
