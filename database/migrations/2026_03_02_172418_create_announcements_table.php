<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('audience')->default('all'); // all, specific
            $table->boolean('send_email')->default(false);
            $table->boolean('send_sms')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('publish_at')->nullable();

            // Keep created_by as column name
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete(); // sets created_by to null if user is deleted

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
