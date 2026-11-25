<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create default roles
        $roles = [
            [
                'role_name' => 'Super Admin',
                'role_code' => 'super_admin',
                'description' => 'Full system access',
                'is_system_role' => true,
                'is_active' => true,
            ],
            [
                'role_name' => 'Branch Admin',
                'role_code' => 'branch_admin',
                'description' => 'Manage assigned branch',
                'is_system_role' => true,
                'is_active' => true,
            ],
            [
                'role_name' => 'Manager',
                'role_code' => 'manager',
                'description' => 'Manage daily branch operations',
                'is_system_role' => true,
                'is_active' => true,
            ],
            [
                'role_name' => 'Cashier',
                'role_code' => 'cashier',
                'description' => 'Manage POS and billing',
                'is_system_role' => true,
                'is_active' => true,
            ],
            [
                'role_name' => 'Staff',
                'role_code' => 'staff',
                'description' => 'Perform assigned daily tasks',
                'is_system_role' => true,
                'is_active' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['role_code' => $roleData['role_code']],
                $roleData
            );
        }

        // 2. Define system permissions
        $permissions = [
            'dashboard' => [
                'dashboard.view' => 'View Dashboard',
            ],

            'orders' => [
                'orders.view' => 'View Orders',
                'orders.create' => 'Create Orders',
                'orders.update' => 'Update Orders',
                'orders.cancel' => 'Cancel Orders',
            ],

            'pos' => [
                'pos.access' => 'Access POS',
            ],

            'customers' => [
                'customers.view' => 'View Customers',
                'customers.create' => 'Create Customers',
                'customers.update' => 'Update Customers',
            ],

            'products' => [
                'products.view' => 'View Products',
                'products.create' => 'Create Products',
                'products.update' => 'Update Products',
                'products.delete' => 'Delete Products',
            ],

            'categories' => [
                'categories.manage' => 'Manage Categories',
            ],

            'inventory' => [
                'inventory.view' => 'View Inventory',
                'inventory.manage' => 'Manage Inventory',
            ],

            'suppliers' => [
                'suppliers.manage' => 'Manage Suppliers',
            ],

            'purchases' => [
                'purchases.view' => 'View Purchases',
                'purchases.create' => 'Create Purchases',
                'purchases.update' => 'Update Purchases',
            ],

            'employees' => [
                'employees.view' => 'View Employees',
                'employees.manage' => 'Manage Employees',
            ],

            'expenses' => [
                'expenses.view' => 'View Expenses',
                'expenses.manage' => 'Manage Expenses',
            ],

            'reports' => [
                'reports.view' => 'View Reports',
                'reports.export' => 'Export Reports',
            ],

            'administration' => [
                'branches.manage' => 'Manage Branches',
                'users.manage' => 'Manage Users',
                'roles_permissions.manage' => 'Manage Roles and Permissions',
                'company.settings.manage' => 'Manage Company Settings',
            ],
        ];

        $permissionIds = [];

        foreach ($permissions as $module => $modulePermissions) {
            foreach ($modulePermissions as $code => $name) {
                $permission = Permission::updateOrCreate(
                    ['permission_code' => $code],
                    [
                        'permission_name' => $name,
                        'module' => $module,
                    ]
                );

                $permissionIds[$code] = $permission->id;
            }
        }

        // 3. Assign permissions to roles
        $rolePermissions = [
            'super_admin' => array_keys($permissionIds),

            'branch_admin' => [
                'dashboard.view',
                'orders.view', 'orders.create', 'orders.update', 'orders.cancel',
                'pos.access',
                'customers.view', 'customers.create', 'customers.update',
                'products.view', 'products.create', 'products.update', 'products.delete',
                'categories.manage',
                'inventory.view', 'inventory.manage',
                'suppliers.manage',
                'purchases.view', 'purchases.create', 'purchases.update',
                'employees.view', 'employees.manage',
                'expenses.view', 'expenses.manage',
                'reports.view', 'reports.export',
                'users.manage',
            ],

            'manager' => [
                'dashboard.view',
                'orders.view', 'orders.create', 'orders.update',
                'pos.access',
                'customers.view', 'customers.create', 'customers.update',
                'products.view', 'products.update',
                'categories.manage',
                'inventory.view', 'inventory.manage',
                'suppliers.manage',
                'purchases.view', 'purchases.create', 'purchases.update',
                'employees.view',
                'expenses.view', 'expenses.manage',
                'reports.view', 'reports.export',
            ],

            'cashier' => [
                'dashboard.view',
                'orders.view', 'orders.create',
                'pos.access',
                'customers.view', 'customers.create',
                'products.view',
            ],

            'staff' => [
                'dashboard.view',
                'orders.view',
                'products.view',
                'inventory.view',
            ],
        ];

        foreach ($rolePermissions as $roleCode => $codes) {
            $role = Role::where('role_code', $roleCode)->firstOrFail();

            $role->permissions()->sync(
                collect($codes)
                    ->map(fn ($code) => $permissionIds[$code])
                    ->all()
            );
        }
    }
}
