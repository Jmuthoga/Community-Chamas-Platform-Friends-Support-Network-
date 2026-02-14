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
        Schema::table('contribution_payments', function (Blueprint $table) {
            $table->string('checkout_request_id')->nullable();
            $table->string('mpesa_receipt')->nullable();
            $table->string('phone')->nullable();
            $table->string('status')->default('pending');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contribution_payments', function (Blueprint $table) {
            //
        });
    }
};
