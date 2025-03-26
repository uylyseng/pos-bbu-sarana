<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\PermissionModel;
use App\Models\PermissionRole;
use Illuminate\Support\Str;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create basic roles
        $roles = [
            'admin' => 'Administrator with full system access',
            'manager' => 'Store manager with administrative access',
            'cashier' => 'Cashier with POS and sales access',
            'inventory' => 'Inventory manager with products and stock access',
            'staff' => 'General staff with limited access'
        ];

        foreach ($roles as $roleName => $description) {
            $role = Role::firstOrCreate(['name' => $roleName]);

            // Now assign permissions based on the role
            $this->assignPermissionsToRole($role->id, $roleName);

            $this->command->info("Created role: {$roleName}");
        }
    }

    /**
     * Assign appropriate permissions to each role
     */
    private function assignPermissionsToRole(int $roleId, string $roleName): void
    {
        // Get all permissions
        $allPermissions = PermissionModel::all();
        $permissionIds = [];

        // Determine which permissions to assign based on role
        switch ($roleName) {
            case 'admin':
                // Admin gets all permissions
                $permissionIds = $allPermissions->pluck('id')->toArray();
                break;

            case 'manager':
                // Manager gets most permissions except user/role management
                $permissionIds = $allPermissions->filter(function ($permission) {
                    // Exclude high-level administrative permissions
                    return !Str::contains($permission->slug, ['delete-role', 'add-role']);
                })->pluck('id')->toArray();
                break;

            case 'cashier':
                // Cashier gets POS, sale, and customer related permissions
                $cashierSlugs = [
                    'dashboard', 'sale', 'cashier', 'pos', 'customers',
                    'payment_methods', 'coupon'
                ];
                $permissionIds = $this->getPermissionIdsBySlug($allPermissions, $cashierSlugs);
                break;

            case 'inventory':
                // Inventory gets product and stock related permissions
                $inventorySlugs = [
                    'dashboard', 'categories', 'products', 'product_sizes',
                    'product_toppings', 'sizes', 'units', 'purchase', 'supplier'
                ];
                $permissionIds = $this->getPermissionIdsBySlug($allPermissions, $inventorySlugs);
                break;

            case 'staff':
                // Staff gets basic access
                $staffSlugs = ['dashboard', 'pos'];
                $permissionIds = $this->getPermissionIdsBySlug($allPermissions, $staffSlugs);
                break;
        }

        // Insert permissions for this role
        if (!empty($permissionIds)) {
            PermissionRole::InsertUpdateRecord($permissionIds, $roleId);
        }
    }

    /**
     * Get permission IDs by their slugs
     */
    private function getPermissionIdsBySlug($allPermissions, array $slugs): array
    {
        return $allPermissions->filter(function ($permission) use ($slugs) {
            foreach ($slugs as $slug) {
                if (Str::contains($permission->slug, $slug)) {
                    return true;
                }
            }
            return false;
        })->pluck('id')->toArray();
    }
}
