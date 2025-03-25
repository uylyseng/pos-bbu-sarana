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
        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->unsignedInteger('currency_from');
            $table->unsignedInteger('currency_to');
            $table->decimal('rate', 10, 4);
            $table->timestamps();
            $table->foreign('currency_from')->references('id')->on('currencies');
            $table->foreign('currency_to')->references('id')->on('currencies');
            $table->unique(['currency_from', 'currency_to']);
           
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_rates');
    }
};
