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
        Schema::create('withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fundraiser_id')->constrained()->onDelete('cascade');
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2); // Requested amount
            $table->decimal('ads_fee', 15, 2)->default(0);
            $table->decimal('ads_vat', 15, 2)->default(0);
            $table->decimal('platform_fee', 15, 2)->default(0);
            $table->decimal('optimization_fee', 15, 2)->default(0);
            $table->decimal('merchant_fee', 15, 2)->default(0);
            $table->decimal('net_amount', 15, 2);
            $table->enum('status', ['pending', 'success', 'rejected'])->default('pending');
            $table->string('proof_image')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('withdrawals');
    }
};
