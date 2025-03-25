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
        Schema::create('purchase_items', function (Blueprint $table) {
            $table->increments('id');
            
            $table->unsignedInteger('purchase_id');         // Link to parent purchase
            $table->unsignedInteger('product_id')->nullable(); // Make this column nullable
            $table->unsignedInteger('purchase_unit_id')->nullable(); // Unit used for purchase (from units table)
            $table->unsignedInteger('quantity');              // Quantity purchased
            $table->decimal('unit_price', 10, 2);               // Price per unit
            $table->decimal('subtotal', 10, 2);                 // Typically: quantity * unit_price
            $table->text('details')->nullable();              // Optional: additional details
            
            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('purchase_id')
                  ->references('id')->on('purchases')
                  ->onDelete('cascade');
            
            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('set null');
            
            $table->foreign('purchase_unit_id')
                  ->references('id')->on('units')
                  ->onDelete('set null');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_items');
    }
};
