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
        Schema::table('campaigns', function (Blueprint $table) {
            if (Schema::hasColumn('campaigns', 'is_initiative')) {
                $table->dropColumn('is_initiative');
            }
            if (Schema::hasColumn('campaigns', 'is_kebaikan')) {
                $table->dropColumn('is_kebaikan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('campaigns', 'is_initiative')) {
                $table->boolean('is_initiative')->default(false);
            }
            if (!Schema::hasColumn('campaigns', 'is_kebaikan')) {
                $table->boolean('is_kebaikan')->default(false);
            }
        });
    }
};
