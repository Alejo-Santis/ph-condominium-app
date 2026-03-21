<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // Define permissions
        $permissions = [
            // Properties
            'view-properties',
            'create-properties',
            'edit-properties',
            'delete-properties',

            // Towers
            'view-towers',
            'create-towers',
            'edit-towers',
            'delete-towers',

            // Units
            'view-units',
            'create-units',
            'edit-units',
            'delete-units',

            // Persons
            'view-persons',
            'create-persons',
            'edit-persons',
            'delete-persons',

            // Charges
            'view-charges',
            'create-charges',
            'edit-charges',
            'delete-charges',

            // Payments
            'view-payments',
            'process-payments',

            // Documents
            'view-documents',
            'upload-documents',
            'delete-documents',

            // Reports
            'view-reports',
            'export-reports',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define roles
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin_property', 'guard_name' => 'web']);
        $ownerRole = Role::firstOrCreate(['name' => 'owner', 'guard_name' => 'web']);
        $tenantRole = Role::firstOrCreate(['name' => 'tenant', 'guard_name' => 'web']);

        // Assign permissions to roles
        // Super Admin - all permissions
        $superAdminRole->syncPermissions($permissions);

        // Admin Property - most permissions except delete
        $adminPermissions = array_filter($permissions, function ($p) {
            return !str_ends_with($p, '-properties') && !str_ends_with($p, '-towers') && !str_ends_with($p, '-units');
        });
        $adminPermissions = array_merge($adminPermissions, [
            'view-properties',
            'create-properties',
            'edit-properties',
            'view-towers',
            'create-towers',
            'edit-towers',
            'view-units',
            'view-persons',
            'view-charges',
            'create-charges',
            'edit-charges',
            'view-payments',
            'view-documents',
            'upload-documents',
            'view-reports',
            'export-reports',
        ]);
        $adminRole->syncPermissions($adminPermissions);

        // Owner - view and manage own units
        $ownerRole->syncPermissions([
            'view-units',
            'view-persons',
            'view-charges',
            'process-payments',
            'view-payments',
            'view-documents',
            'upload-documents',
            'view-reports',
        ]);

        // Tenant - limited viewing
        $tenantRole->syncPermissions([
            'view-charges',
            'process-payments',
            'view-payments',
            'view-documents',
        ]);
    }
}
