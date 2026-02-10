<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('monthly_contributions', function (Blueprint $table) {
            $table->id();

            // Match the type with users.id (INT UNSIGNED)
            $table->unsignedInteger('user_id');

            // Explicit foreign key
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->integer('month');
            $table->integer('year');

            $table->decimal('amount_due', 10, 2)->default(500);
            $table->decimal('penalty', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(500);

            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');

            $table->timestamp('paid_at')->nullable();

            $table->timestamps();

            // Prevent duplicate month records
            $table->unique(['user_id', 'month', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_contributions');
    }
};
