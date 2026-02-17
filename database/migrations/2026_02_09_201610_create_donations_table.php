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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained('users', 'id')->onDelete('cascade');
            $table->string('donor_name');
            $table->string('donor_phone');
            $table->decimal('amount', 15, 2);
            $table->string('payment_method');
            $table->string('payment_channel');
            $table->string('payment_code');
            $table->string('payment_proof')->nullable();
            $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('expired_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
