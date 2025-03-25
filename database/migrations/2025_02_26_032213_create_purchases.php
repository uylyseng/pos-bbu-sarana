<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->increments('id');
            
            // Supplier and reference
            $table->unsignedInteger('supplier_id')->nullable(); // Link to the supplier
            $table->string('reference', 50)->nullable();         // External reference/invoice number
            
            // Purchase date and financial details
            $table->timestamp('purchase_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('total', 10, 2);
            
            // Payment method: one payment method per purchase (e.g., cash, ABA, etc.)
            $table->unsignedInteger('payment_method_id')->nullable();
            
            // Status: When 'complete', application logic should update product quantities.
            $table->enum('status', ['pending', 'complete'])->default('pending');
            
            // Audit fields
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign key constraints
            $table->foreign('supplier_id')
                  ->references('id')->on('suppliers')
                  ->onDelete('set null');
            $table->foreign('payment_method_id')
                  ->references('id')->on('payment_methods')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
