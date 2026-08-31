<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            // Super Admin
            'manage_users', 'manage_roles', 'manage_permissions', 'backup_database',
            'manage_banners', 'manage_menus', 'manage_site_settings', 'manage_posts',
            // Hubin & BKK
            'manage_dudi_partners', 'manage_job_vacancies', 'manage_tracer_studies',
            // Koperasi & TeFA
            'manage_tefa_products', 'manage_tefa_orders',
            // Guru & Akademik
            'manage_learning_modules', 'input_grades',
            // Eskul
            'manage_extracurriculars', 'approve_registrations', 'manage_achievements',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['permission_name' => $name]);
        }

        // Wire a sensible default role -> permission map. This is seed data,
        // not a schema constraint, so it stays fully editable at runtime.
        $map = [
            'Super Admin' => $permissions, // full access
            'Hubin' => ['manage_dudi_partners', 'manage_job_vacancies', 'manage_tracer_studies'],
            'Koperasi' => ['manage_tefa_products', 'manage_tefa_orders'],
            'Guru' => ['manage_learning_modules', 'input_grades'],
            'Eskul' => ['manage_extracurriculars', 'approve_registrations', 'manage_achievements'],
        ];

        foreach ($map as $roleName => $perms) {
            $role = Role::where('role_name', $roleName)->first();
            if (! $role) {
                continue;
            }
            $permIds = Permission::whereIn('permission_name', $perms)->pluck('id');
            foreach ($permIds as $permId) {
                DB::table('role_permission')->updateOrInsert(
                    ['role_id' => $role->id, 'permission_id' => $permId],
                    ['created_at' => now(), 'updated_at' => now()]
                );
            }
        }
    }
}
