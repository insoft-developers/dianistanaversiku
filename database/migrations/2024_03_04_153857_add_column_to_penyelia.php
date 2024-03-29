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
        Schema::table('penyelia', function (Blueprint $table) {
            $table->unsignedInteger('id_kategori')->after("id")->index()->nullable();
            $table->foreign('id_kategori')->references('id')->on('penyelia_kategori')->cascadeOnUpdate()->nullOnDelete();
            $table->string('no_telp')->after("name_penyelia")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penyelia', function (Blueprint $table) {
            $table->dropForeign(["id_kategori"]);
            $table->dropIndex(["id_kategori"]);
            $table->dropColumn("id_kategori");
            $table->dropColumn("no_telp");
        });
    }
};
