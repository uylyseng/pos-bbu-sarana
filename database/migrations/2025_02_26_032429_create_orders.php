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
        Schema::create('orders', function (Blueprint $table) {

                  $table->increments('id');
                  // Assuming the customers and coffee_tables tables use increments, these remain unsignedInteger
                  $table->unsignedInteger('customer_id');
                  $table->unsignedInteger('table_id');
                  $table->enum('status', ['hold', 'complete'])->default('hold');
                  $table->decimal('subtotal', 10, 2);
                  $table->decimal('discount', 10, 2)->default(0);
                  $table->decimal('total', 10, 2);
                  $table->integer('total_item')->default(0);
                  $table->decimal('product_discount', 10, 2)->default(0.00);
                  $table->decimal('order_discount', 10, 2)->default(0.00);
                  $table->unsignedBigInteger('deleted_by')->nullable();
                  $table->softDeletes(); // Enable soft delete (deleted_at column)
                  $table->timestamps();
                  $table->unsignedBigInteger('created_by')->nullable();
                  $table->unsignedBigInteger('updated_by')->nullable();
                  // Define foreign key constraints (adding onDelete cascade for good measure)
                  $table->foreign('created_by')
                        ->references('id')->on('users')->onDelete('set null');
                  $table->foreign('updated_by')
                        ->references('id')->on('users')->onDelete('set null');
                  $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
                  $table->foreign('table_id')->references('id')->on('tables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
