<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\User;

class UsersSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = collect([
            [
                'name' => "Super Admin",
                'email' => 'superadmin@gmail.com',
                'role' => Role::SUPERADMIN,
            ],
            [
                'name' => "usertest1",
                'email' => 'usertest1@gmail.com',
                'role' => Role::USER,
            ],
        ]);

        $data->map(function ($data) {
            $name = $data['name'];
            $email = $data['email'];
            $role = $data['role'];
            $email_verified_at = now();
            $password = bcrypt('000000');

            $user = User::query()
                ->updateOrCreate(
                    compact('name', 'email', 'email_verified_at', 'role', 'password'),
                    compact('name', 'email', 'email_verified_at', 'role', 'password')
                );

            $user->syncRoles($user->role);
        });
    }
}
