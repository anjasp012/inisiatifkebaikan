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
            if (Schema::hasColumn('fundraisers', 'permit_doc')) {
                $table->dropColumn('permit_doc');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fundraisers', function (Blueprint $table) {
            $table->string('permit_doc')->nullable();
        });
    }
};
