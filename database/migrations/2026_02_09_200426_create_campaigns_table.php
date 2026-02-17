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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('campaign_categories')->onDelete('cascade');
            $table->foreignId('fundraiser_id')->nullable()->constrained('fundraisers')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->string('slug');
            $table->text('description');
            $table->decimal('target_amount', 15, 2);
            $table->decimal('collected_amount', 15, 2)->default(0);
            $table->enum('status', ['active', 'completed', 'hidden'])->default('active');
            $table->boolean('is_emergency')->default(false);
            $table->boolean('is_priority')->default(false);

            $table->boolean('is_optimized')->default(false);
            $table->string('thumbnail');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
