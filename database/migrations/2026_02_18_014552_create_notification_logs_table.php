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
        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donation_id')->nullable();
            $table->string('type')->comment('whatsapp');
            $table->string('recipient');
            $table->text('message');
            $table->string('status')->comment('success, failed');
            $table->text('error_message')->nullable();
            $table->json('response_data')->nullable();
            $table->timestamps();

            $table->foreign('donation_id')->references('id')->on('donations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
