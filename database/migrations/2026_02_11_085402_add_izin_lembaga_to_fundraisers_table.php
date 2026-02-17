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
        Schema::table('fundraisers', function (Blueprint $table) {
            $table->string('izin_lembaga')->nullable()->after('sk_kumham');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fundraisers', function (Blueprint $table) {
            $table->dropColumn('izin_lembaga');
        });
    }
};
