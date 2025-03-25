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
        Schema::create('order_item_topping', function (Blueprint $table) {
            $table->increments('id'); // INT UNSIGNED primary key
            $table->unsignedInteger('order_item_id');  // must match increments
            $table->unsignedInteger('product_topping_id');
            $table->timestamps();
        
            // Foreign keys
            $table->foreign('order_item_id')
                  ->references('id')->on('order_items')
                  ->onDelete('cascade');
        
            $table->foreign('product_topping_id')
                  ->references('id')->on('product_toppings')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_item_topping');
    }
};
