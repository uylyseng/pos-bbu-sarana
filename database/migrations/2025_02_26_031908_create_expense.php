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
    { Schema::create('expenses', function (Blueprint $table) {
        $table->increments('id');
        
        // Expense Type and Payment Method
        $table->unsignedInteger('expense_type_id'); // Links to the expense_types table
        $table->unsignedInteger('payment_method_id')->nullable(); // Payment method used for the expense
    
        // External reference/invoice number
        $table->string('reference', 50)->nullable();
        
        // Expense date and amount
        $table->timestamp('expense_date')->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->decimal('amount', 10, 2);
        $table->text('description')->nullable();
        
        // Attachment: Stores file path or URL for an attached document (e.g. invoice, receipt)
        $table->string('attachment', 255)->nullable();
        
        // Audit fields
        $table->unsignedBigInteger('created_by')->nullable();
        $table->unsignedBigInteger('updated_by')->nullable();
        $table->unsignedBigInteger('deleted_by')->nullable();
        
        $table->timestamps();
        $table->softDeletes(); // Allows for soft deletion of expense records
        
        // Foreign key constraints
        $table->foreign('expense_type_id')
              ->references('id')->on('expense_types')
              ->onDelete('cascade');
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
        Schema::dropIfExists('expense');
    }
};
