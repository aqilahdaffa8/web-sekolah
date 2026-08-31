<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@smk.sch.id'],
            [
                'name' => 'Super Admin',
                // CHANGE THIS PASSWORD immediately after first login/seed.
                'password' => Hash::make('ChangeMe!12345'),
            ]
        );

        $role = Role::where('role_name', 'Super Admin')->first();

        if ($role) {
            $superAdmin->roles()->syncWithoutDetaching([$role->id]);
        }
    }
}
