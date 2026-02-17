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
        Schema::create('fundraisers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users', 'id')->onDelete('cascade');
            $table->string('foundation_name')->nullable();
            $table->string('sk_kumham')->nullable();
            $table->string('akta_notaris')->nullable();
            $table->string('npwp')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('office_address')->nullable();
            $table->string('office_photo')->nullable();
            $table->string('logo')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fundraisers');
    }
};
