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
        Schema::create('contribution_payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('contribution_id');
            $table->foreign('contribution_id')
                ->references('id')
                ->on('monthly_contributions')
                ->onDelete('cascade');

            $table->unsignedInteger('user_id');

            $table->decimal('amount', 10, 2);

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contribution_payments');
    }
};
