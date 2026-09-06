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
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@smk.sch.id',
                'password' => 'ChangeMe!12345',
                'role' => 'Super Admin'
            ],
            [
                'name' => 'Admin Hubin',
                'email' => 'hubin@smk.sch.id',
                'password' => 'password123',
                'role' => 'Hubin'
            ],
            [
                'name' => 'Admin Koperasi',
                'email' => 'koperasi@smk.sch.id',
                'password' => 'password123',
                'role' => 'Koperasi'
            ],
            [
                'name' => 'Bapak Guru',
                'email' => 'guru@smk.sch.id',
                'password' => 'password123',
                'role' => 'Guru'
            ],
            [
                'name' => 'Pembina Eskul',
                'email' => 'eskul@smk.sch.id',
                'password' => 'password123',
                'role' => 'Eskul'
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => Hash::make($userData['password']),
                ]
            );

            $role = Role::where('role_name', $userData['role'])->first();
            if ($role) {
                $user->roles()->syncWithoutDetaching([$role->id]);
            }
        }
    }
}
