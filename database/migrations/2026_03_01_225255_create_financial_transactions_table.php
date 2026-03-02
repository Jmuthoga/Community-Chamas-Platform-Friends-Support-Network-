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
        Schema::create('financial_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
            $table->string('reference')->nullable(); // MPESA receipt or manual ref
            $table->enum('type', [
                'contribution',
                'loan_repayment',
                'donation',
                'adjustment',
                'other'
            ]);
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->enum('payment_method', [
                'mpesa',
                'bank',
                'cash',
                'cheque',
                'other'
            ])->default('mpesa');
            $table->timestamp('transaction_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_transactions');
    }
};
