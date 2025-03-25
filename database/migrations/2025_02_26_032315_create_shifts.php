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
        Schema::create('shifts', function (Blueprint $table) {
            // Primary key: unsigned big integer for shifts
            $table->bigIncrements('id');
            
            // User responsible for the shift
            $table->unsignedBigInteger('user_id');
            
            // Shift timing
            $table->dateTime('time_open')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('time_close')->nullable();
            
            // Cash tracking fields
            $table->decimal('cash_in_hand', 10, 2)->default(0);
            $table->decimal('cash_submitted', 10, 2)->default(0);
            $table->decimal('total_cash', 10, 2)->default(0); // Total cash recorded during the shift
            
            // Optional notes for the shift
            $table->text('notes')->nullable();
            
            // New: Shift status (e.g., 'open' for an ongoing shift, 'closed' when finished)
            $table->enum('status', ['open', 'closed'])->default('open');
            
            // Audit fields to track who created, updated, or deleted the record
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            
            // Timestamps for created_at and updated_at
            $table->timestamps();
            
            // Foreign key constraint for the user
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
