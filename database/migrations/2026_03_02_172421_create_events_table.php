<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('location')->nullable();
            $table->date('event_date');
            $table->time('event_time')->nullable();
            $table->boolean('send_email')->default(false);
            $table->boolean('send_sms')->default(false);
            $table->boolean('is_active')->default(true);

            // Use created_by with unsignedInteger to match users.id and avoid FK errors
            $table->unsignedInteger('created_by')->nullable();
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete(); // sets created_by to null if the user is deleted

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
