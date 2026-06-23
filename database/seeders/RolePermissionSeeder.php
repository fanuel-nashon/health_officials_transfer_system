<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'submit_transfer',
            'view_own_transfers',
            'review_transfer_facility',
            'review_transfer_district',
            'review_transfer_region',
            'review_transfer_ministry',
            'manage_users',
            'manage_facilities',
            'manage_locations',
            'view_all_transfers',
            'view_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $roles = [
            'admin' => [
                'manage_users', 'manage_facilities', 'manage_locations',
                'view_all_transfers', 'view_reports',
            ],
            'nurse_doctor' => [
                'submit_transfer', 'view_own_transfers',
            ],
            'facility_admin' => [
                'review_transfer_facility', 'view_all_transfers', 'view_reports',
            ],
            'district_officer' => [
                'review_transfer_district', 'view_all_transfers', 'view_reports',
            ],
            'region_officer' => [
                'review_transfer_region', 'view_all_transfers', 'view_reports',
            ],
            'ministry_official' => [
                'review_transfer_ministry', 'view_all_transfers', 'view_reports',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($rolePermissions);
        }
    }
}
