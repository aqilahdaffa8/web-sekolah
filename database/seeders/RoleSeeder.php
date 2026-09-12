<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Minimal starter set. New roles can be added later with a simple
     * INSERT — no migration needed, no code change to the RBAC middleware.
     */
    public function run(): void
    {
        $roles = ['Super Admin', 'Hubin', 'Koperasi', 'Guru', 'Eskul', 'Siswa'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['role_name' => $role]);
        }
    }
}
