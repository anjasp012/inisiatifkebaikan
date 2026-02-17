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
        Schema::create('payment_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained('donations')->onDelete('cascade');
            $table->string('file_path');
            $table->decimal('claimed_amount', 15, 2)->nullable(); // Nominal yang diklaim di bukti transfer
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Remove payment_proof column from donations table
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('payment_proof');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add back payment_proof column to donations table
        Schema::table('donations', function (Blueprint $table) {
            $table->string('payment_proof')->nullable();
        });

        Schema::dropIfExists('payment_proofs');
    }
};
