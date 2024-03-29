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
        Schema::table('banner_iklan', function (Blueprint $table) {
            $table->mediumText('content')->nullable()->after("link_terkait");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner_iklan', function (Blueprint $table) {
            $table->dropColumn("content");
        });
    }
};
