<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Lead permissions
        Permission::create(['name' => 'leads.view']);
        Permission::create(['name' => 'leads.create']);
        Permission::create(['name' => 'leads.edit']);
        Permission::create(['name' => 'leads.delete']);
        Permission::create(['name' => 'leads.assign']);
        Permission::create(['name' => 'leads.export']);

        // Property permissions
        Permission::create(['name' => 'properties.view']);
        Permission::create(['name' => 'properties.create']);
        Permission::create(['name' => 'properties.edit']);
        Permission::create(['name' => 'properties.delete']);

        // Client permissions
        Permission::create(['name' => 'clients.view']);
        Permission::create(['name' => 'clients.create']);
        Permission::create(['name' => 'clients.edit']);

        // Follow-up permissions
        Permission::create(['name' => 'follow_ups.view']);
        Permission::create(['name' => 'follow_ups.create']);
        Permission::create(['name' => 'follow_ups.edit']);

        // Dashboard & reports
        Permission::create(['name' => 'dashboard.view']);
        Permission::create(['name' => 'reports.view']);

        // User management
        Permission::create(['name' => 'users.view']);
        Permission::create(['name' => 'users.create']);
        Permission::create(['name' => 'users.edit']);
        Permission::create(['name' => 'users.delete']);

        // Super Admin - owner level, all permissions
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - all permissions
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // Manager - everything except user management & property delete
        $manager = Role::create(['name' => 'manager']);
        $manager->givePermissionTo([
            'leads.view', 'leads.create', 'leads.edit', 'leads.delete', 'leads.assign', 'leads.export',
            'properties.view', 'properties.create', 'properties.edit',
            'clients.view', 'clients.create', 'clients.edit',
            'follow_ups.view', 'follow_ups.create', 'follow_ups.edit',
            'dashboard.view', 'reports.view',
            'users.view',
        ]);

        // Sales Agent - lead management, viewing properties, follow-ups
        $agent = Role::create(['name' => 'sales_agent']);
        $agent->givePermissionTo([
            'leads.view', 'leads.create', 'leads.edit',
            'properties.view',
            'clients.view', 'clients.create', 'clients.edit',
            'follow_ups.view', 'follow_ups.create', 'follow_ups.edit',
            'dashboard.view',
        ]);
    }
}
