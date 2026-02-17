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
        // 1. Remove redundant campaign_views table
        Schema::dropIfExists('campaign_views');

        // 2. Remove redundant views_count from articles
        if (Schema::hasColumn('articles', 'views_count')) {
            Schema::table('articles', function (Blueprint $table) {
                $table->dropColumn('views_count');
            });
        }

        // 3. Rename columns in fundraisers for consistency (ID to EN)
        Schema::table('fundraisers', function (Blueprint $table) {
            $table->renameColumn('sk_kumham', 'legal_doc');
            $table->renameColumn('akta_notaris', 'notary_doc');
            $table->renameColumn('izin_lembaga', 'permit_doc');
            $table->renameColumn('npwp', 'tax_id');
            $table->renameColumn('office_photo', 'office_image');
            $table->renameColumn('logo', 'logo_image');
        });

        // 4. Standardize proof columns to 'file_path'
        Schema::table('distributions', function (Blueprint $table) {
            $table->renameColumn('proof_image', 'file_path');
        });

        Schema::table('withdrawals', function (Blueprint $table) {
            $table->renameColumn('proof_image', 'file_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('withdrawals', function (Blueprint $table) {
            $table->renameColumn('file_path', 'proof_image');
        });

        Schema::table('distributions', function (Blueprint $table) {
            $table->renameColumn('file_path', 'proof_image');
        });

        Schema::table('fundraisers', function (Blueprint $table) {
            $table->renameColumn('logo_image', 'logo');
            $table->renameColumn('office_image', 'office_photo');
            $table->renameColumn('tax_id', 'npwp');
            $table->renameColumn('permit_doc', 'izin_lembaga');
            $table->renameColumn('notary_doc', 'akta_notaris');
            $table->renameColumn('legal_doc', 'sk_kumham');
        });

        Schema::table('articles', function (Blueprint $table) {
            $table->integer('views_count')->default(0);
        });

        Schema::create('campaign_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained('campaigns')->onDelete('cascade');
            $table->string('ip_address');
            $table->string('user_agent');
            $table->timestamps();
        });
    }
};
