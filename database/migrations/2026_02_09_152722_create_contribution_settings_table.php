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
        Schema::create('contribution_settings', function (Blueprint $table) {

            $table->id();

            $table->decimal('monthly_amount', 10, 2)->default(500);
            $table->decimal('penalty_per_day', 10, 2)->default(100);

            $table->integer('due_day')->default(15);
            $table->integer('grace_day')->default(16);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contribution_settings');
    }
};
