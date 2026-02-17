<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donation_id')->constrained('donations')->onDelete('cascade');
            $table->string('file_path');
            $table->timestamps();
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('payment_proof');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('payment_proof')->nullable();
        });

        Schema::dropIfExists('payment_proofs');
    }
};
