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
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id');
            $table->unsignedInteger('payment_method_id');
            $table->decimal('amount', 10, 2);
            $table->decimal('changeUSD', 10, 2)->default(0);
            $table->decimal('changeKHR', 10, 2)->default(0);
            $table->timestamp('payment_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('notes')->nullable();
            
            // Add status field with default value 'pending'
            $table->enum('status', ['pending', 'completed'])->default('pending');
            
            $table->timestamps();
        
            $table->foreign('order_id')
                  ->references('id')->on('orders');
            $table->foreign('payment_method_id')
                  ->references('id')->on('payment_methods');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
