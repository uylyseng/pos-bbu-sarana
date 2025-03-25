<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            // Primary key
            $table->increments('id');
            
            // Product identification fields
            $table->string('barcode')->unique()->nullable();
            $table->string('name_en', 100);
            $table->string('name_kh', 100)->nullable();
            
            // Pricing and inventory fields
            $table->decimal('base_price', 10, 2);
            // Separate columns for purchase unit and sale unit (referencing the units table)
            $table->unsignedInteger('purchase_unit_id')->nullable();
            $table->unsignedInteger('sale_unit_id')->nullable();
            $table->unsignedInteger('category_id')->nullable();
            
            // Quantity field – defaults to 0
            $table->decimal('qty', 10, 2)->default(0);
            
            // Image path and active status
            $table->string('image', 255)->nullable();
            $table->enum('active', ['active', 'inactive'])->default('active');
            $table->enum('has_size', ['has', 'none'])->default('has');
            $table->enum('has_topping', ['has', 'none'])->default('has');
            $table->enum('is_stock', ['has_stock', 'none_stock'])->default('has_stock');
            
            // Optional low stock threshold value
            $table->integer('low_stock')->nullable();
            
            // Audit fields for tracking user activity
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            
            // Timestamps for created_at and updated_at
            $table->timestamps();
            
            // Soft delete column for soft deleting records
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('purchase_unit_id')
                  ->references('id')->on('units')
                  ->onDelete('set null');
            $table->foreign('sale_unit_id')
                  ->references('id')->on('units')
                  ->onDelete('set null');
            $table->foreign('category_id')
                  ->references('id')->on('categories')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
