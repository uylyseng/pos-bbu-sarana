<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Import Carbon for timestamps

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now(); // Get the current timestamp

        $permissions = [
            // Dashboard permission
            ['name' => 'Dashboard', 'slug' => 'dashboard', 'group_by' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Sale', 'slug' => 'sale', 'group_by' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Cashier', 'slug' => 'cashier', 'group_by' => 0, 'created_at' => $now, 'updated_at' => $now],

            // POS permission
            ['name' => 'POS', 'slug' => 'pos', 'group_by' => 1, 'created_at' => $now, 'updated_at' => $now],

            // Category permissions
            ['name' => 'Categories', 'slug' => 'categories', 'group_by' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Category', 'slug' => 'add-category', 'group_by' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Category', 'slug' => 'edit-category', 'group_by' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Category', 'slug' => 'update-category', 'group_by' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Category', 'slug' => 'delete-category', 'group_by' => 2, 'created_at' => $now, 'updated_at' => $now],

            // Products permissions
            ['name' => 'Products', 'slug' => 'products', 'group_by' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Product', 'slug' => 'add-product', 'group_by' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Product', 'slug' => 'edit-product', 'group_by' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Product', 'slug' => 'update-product', 'group_by' => 3, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Product', 'slug' => 'delete-product', 'group_by' => 3, 'created_at' => $now, 'updated_at' => $now],

            // Product Sizes permissions
            ['name' => 'Product Sizes', 'slug' => 'product_sizes', 'group_by' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Product Size', 'slug' => 'add-product-size', 'group_by' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Product Size', 'slug' => 'edit-product-size', 'group_by' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Product Size', 'slug' => 'update-product-size', 'group_by' => 4, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Product Size', 'slug' => 'delete-product-size', 'group_by' => 4, 'created_at' => $now, 'updated_at' => $now],

            // Product Toppings permissions
            ['name' => 'Product Toppings', 'slug' => 'product_toppings', 'group_by' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Product Topping', 'slug' => 'add-product-topping', 'group_by' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Product Topping', 'slug' => 'edit-product-topping', 'group_by' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Product Topping', 'slug' => 'update-product-topping', 'group_by' => 5, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Product Topping', 'slug' => 'delete-product-topping', 'group_by' => 5, 'created_at' => $now, 'updated_at' => $now],

            // Sizes permissions
            ['name' => 'Sizes', 'slug' => 'sizes', 'group_by' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Size', 'slug' => 'add-size', 'group_by' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Size', 'slug' => 'edit-size', 'group_by' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Size', 'slug' => 'update-size', 'group_by' => 6, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Size', 'slug' => 'delete-size', 'group_by' => 6, 'created_at' => $now, 'updated_at' => $now],

            // Tables permissions
            ['name' => 'Tables', 'slug' => 'tables', 'group_by' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Table', 'slug' => 'add-table', 'group_by' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Table', 'slug' => 'edit-table', 'group_by' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Table', 'slug' => 'update-table', 'group_by' => 7, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Table', 'slug' => 'delete-table', 'group_by' => 7, 'created_at' => $now, 'updated_at' => $now],


            // Group Tables permissions
            ['name' => 'Group Tables', 'slug' => 'group_tables', 'group_by' => 8, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Group Table', 'slug' => 'add-group-table', 'group_by' => 8, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Group Table', 'slug' => 'edit-group-table', 'group_by' => 8, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Group Table', 'slug' => 'update-group-table', 'group_by' => 8, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Group Table', 'slug' => 'delete-group-table', 'group_by' => 8, 'created_at' => $now, 'updated_at' => $now],

            // Group Toppings permissions
            ['name' => 'Group Toppings', 'slug' => 'group_toppings', 'group_by' => 9, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Group Topping', 'slug' => 'add-group-topping', 'group_by' => 9, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Group Topping', 'slug' => 'edit-group-topping', 'group_by' => 9, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Group Topping', 'slug' => 'update-group-topping', 'group_by' => 9, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Group Topping', 'slug' => 'delete-group-topping', 'group_by' => 9, 'created_at' => $now, 'updated_at' => $now],


            // Toppings permissions
            ['name' => 'Toppings', 'slug' => 'toppings', 'group_by' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Topping', 'slug' => 'add-topping', 'group_by' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Topping', 'slug' => 'edit-topping', 'group_by' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Topping', 'slug' => 'update-topping', 'group_by' => 10, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Topping', 'slug' => 'delete-topping', 'group_by' => 10, 'created_at' => $now, 'updated_at' => $now],

            // Units permissions
            ['name' => 'Units', 'slug' => 'units', 'group_by' => 11, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Unit', 'slug' => 'add-unit', 'group_by' => 11, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Unit', 'slug' => 'edit-unit', 'group_by' => 11, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Unit', 'slug' => 'update-unit', 'group_by' => 11, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Unit', 'slug' => 'delete-unit', 'group_by' => 11, 'created_at' => $now, 'updated_at' => $now],

            // Currency permissions
            ['name' => 'Currencies', 'slug' => 'currencies', 'group_by' => 12, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Currency', 'slug' => 'add-currency', 'group_by' => 12, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Currency', 'slug' => 'edit-currency', 'group_by' => 12, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Currency', 'slug' => 'update-currency', 'group_by' => 12, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Currency', 'slug' => 'delete-currency', 'group_by' => 12, 'created_at' => $now, 'updated_at' => $now],

            // Customer permissions
            ['name' => 'Customers', 'slug' => 'customers', 'group_by' => 13, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Customer', 'slug' => 'add-customer', 'group_by' => 13, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Customer', 'slug' => 'edit-customer', 'group_by' => 13, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Customer', 'slug' => 'update-customer', 'group_by' => 13, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Customer', 'slug' => 'delete-customer', 'group_by' => 13, 'created_at' => $now, 'updated_at' => $now],

            // Coupon permissions
            ['name' => 'Coupon', 'slug' => 'coupon', 'group_by' => 14, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Coupon', 'slug' => 'add-coupon', 'group_by' => 14, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Coupon', 'slug' => 'edit-coupon', 'group_by' => 14, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Coupon', 'slug' => 'update-coupon', 'group_by' => 14, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Coupon', 'slug' => 'delete-coupon', 'group_by' => 14, 'created_at' => $now, 'updated_at' => $now],


            // Exchange Rates permissions
            ['name' => 'Exchange Rates', 'slug' => 'exchange_rates', 'group_by' => 15, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Exchange Rate', 'slug' => 'add-exchange-rate', 'group_by' => 15, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Exchange Rate', 'slug' => 'edit-exchange-rate', 'group_by' => 15, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Exchange Rate', 'slug' => 'update-exchange-rate', 'group_by' => 15, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Exchange Rate', 'slug' => 'delete-exchange-rate', 'group_by' => 15, 'created_at' => $now, 'updated_at' => $now],

            // Payment Methods permissions
            ['name' => 'Payment Methods', 'slug' => 'payment_methods', 'group_by' => 16, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Payment Method', 'slug' => 'add-payment-method', 'group_by' => 16, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Payment Method', 'slug' => 'edit-payment-method', 'group_by' => 16, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Payment Method', 'slug' => 'update-payment-method', 'group_by' => 16, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Payment Method', 'slug' => 'delete-payment-method', 'group_by' => 16, 'created_at' => $now, 'updated_at' => $now],

            // Store permissions
            ['name' => 'Stores', 'slug' => 'stores', 'group_by' => 17, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Store', 'slug' => 'add-store', 'group_by' => 17, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Store', 'slug' => 'edit-store', 'group_by' => 17, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Store', 'slug' => 'update-store', 'group_by' => 17, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Store', 'slug' => 'delete-store', 'group_by' => 17, 'created_at' => $now, 'updated_at' => $now],

            // Purchase permissions
            ['name' => 'Purchase', 'slug' => 'purchase', 'group_by' => 18, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Purchase', 'slug' => 'add-purchase', 'group_by' => 18, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Purchase', 'slug' => 'edit-purchase', 'group_by' => 18, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Purchase', 'slug' => 'update-purchase', 'group_by' => 18, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Purchase', 'slug' => 'delete-purchase', 'group_by' => 18, 'created_at' => $now, 'updated_at' => $now],

            // Suppliers permissions
            ['name' => 'Suppliers', 'slug' => 'supplier', 'group_by' => 19, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Supplier', 'slug' => 'add-supplier', 'group_by' => 19, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Supplier', 'slug' => 'edit-supplier', 'group_by' => 19, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Supplier', 'slug' => 'update-supplier', 'group_by' => 19, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Supplier', 'slug' => 'delete-supplier', 'group_by' => 19, 'created_at' => $now, 'updated_at' => $now],

            // Exspense Type permissions
            ['name' => 'Exspense Type', 'slug' => 'exspense-type', 'group_by' => 20, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Exspense Type', 'slug' => 'add-exspense-type', 'group_by' => 20, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Exspense Type', 'slug' => 'edit-exspense-type', 'group_by' => 20, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Exspense Type', 'slug' => 'update-exspense-type', 'group_by' => 20, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Exspense Type', 'slug' => 'delete-exspense-type', 'group_by' => 20, 'created_at' => $now, 'updated_at' => $now],

            // Exspenses permissions
            ['name' => 'Exspenses', 'slug' => 'exspenses', 'group_by' => 21, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Exspenses', 'slug' => 'add-exspense', 'group_by' => 21, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Exspenses', 'slug' => 'edit-exspense', 'group_by' => 21, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Exspenses', 'slug' => 'update-exspense', 'group_by' => 21, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Exspenses', 'slug' => 'delete-exspense', 'group_by' => 21, 'created_at' => $now, 'updated_at' => $now],

            // Report permissions
            ['name' => 'Reports', 'slug' => 'report', 'group_by' => 22, 'created_at' => $now, 'updated_at' => $now],

            // Users permissions
            ['name' => 'Users', 'slug' => 'users', 'group_by' => 23, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add User', 'slug' => 'add-user', 'group_by' => 23, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit User', 'slug' => 'edit-user', 'group_by' => 23, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update User', 'slug' => 'update-user', 'group_by' => 23, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete User', 'slug' => 'delete-user', 'group_by' => 23, 'created_at' => $now, 'updated_at' => $now],

            // Roles permissions
            ['name' => 'Roles', 'slug' => 'roles', 'group_by' => 24, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Add Role', 'slug' => 'add-role', 'group_by' => 24, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Edit Role', 'slug' => 'edit-role', 'group_by' => 24, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Update Role', 'slug' => 'update-role', 'group_by' => 24, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Delete Role', 'slug' => 'delete-role', 'group_by' => 24, 'created_at' => $now, 'updated_at' => $now],

            // // Payments permissions
            // ['name' => 'Payments', 'slug' => 'payments', 'group_by' => 11, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Add Payment', 'slug' => 'add-payment', 'group_by' => 11, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Edit Payment', 'slug' => 'edit-payment', 'group_by' => 11, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Update Payment', 'slug' => 'update-payment', 'group_by' => 11, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Delete Payment', 'slug' => 'delete-payment', 'group_by' => 11, 'created_at' => $now, 'updated_at' => $now],

            // Orders permissions
            // ['name' => 'Orders', 'slug' => 'orders', 'group_by' => 8, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Add Order', 'slug' => 'add-order', 'group_by' => 8, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Edit Order', 'slug' => 'edit-order', 'group_by' => 8, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Update Order', 'slug' => 'update-order', 'group_by' => 8, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Delete Order', 'slug' => 'delete-order', 'group_by' => 8, 'created_at' => $now, 'updated_at' => $now],

            // // Order Items permissions
            // ['name' => 'Order Items', 'slug' => 'order_items', 'group_by' => 9, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Add Order Item', 'slug' => 'add-order-item', 'group_by' => 9, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Edit Order Item', 'slug' => 'edit-order-item', 'group_by' => 9, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Update Order Item', 'slug' => 'update-order-item', 'group_by' => 9, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Delete Order Item', 'slug' => 'delete-order-item', 'group_by' => 9, 'created_at' => $now, 'updated_at' => $now],

            // // Order Item Toppings permissions
            // ['name' => 'Order Item Topping', 'slug' => 'order_item_topping', 'group_by' => 10, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Add Order Item Topping', 'slug' => 'add-order-item-topping', 'group_by' => 10, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Edit Order Item Topping', 'slug' => 'edit-order-item-topping', 'group_by' => 10, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Update Order Item Topping', 'slug' => 'update-order-item-topping', 'group_by' => 10, 'created_at' => $now, 'updated_at' => $now],
            // ['name' => 'Delete Order Item Topping', 'slug' => 'delete-order-item-topping', 'group_by' => 10, 'created_at' => $now, 'updated_at' => $now],

        ];

        DB::table('permissions')->insert($permissions);
    }
}
