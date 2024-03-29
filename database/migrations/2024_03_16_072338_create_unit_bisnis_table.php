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
        Schema::create('unit_bisnis', function (Blueprint $table) {
            $table->id();
            $table->string('name_unit');
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->enum('kategori',["Kolam Renang","Lapangan Basket","Lapangan Tenis","Komunal Space"])->nullable();
            $table->enum("jenis_harga",["Per Jam","Kedatangan"])->nullable();
            // untuk jenis harga per jam
            $table->integer("harga_warga_1721_weekday")->nullable();
            $table->integer("harga_warga_1721_weekend")->nullable();
            $table->integer("harga_umum_0617_weekday")->nullable();
            $table->integer("harga_umum_0617_weekend")->nullable();
            $table->integer("harga_umum_1721_weekday")->nullable();
            $table->integer("harga_umum_1721_weekend")->nullable();

            // untuk jenis harga kedatangan 
            $table->enum("jenis_kedatangan",["Membership","Non Member"])->nullable();
            $table->integer("harga_membership_4x")->nullable();
            $table->integer("harga_membership_8x")->nullable();
            $table->integer("harga_non_member")->nullable();
            $table->integer("harga_tamu_warga")->nullable();
            $table->enum("status_booking",["Aktif","Non Aktif"])->nullable();

            $table->unsignedInteger('id_admin')->index()->nullable();
            $table->foreign('id_admin')->references('id')->on('admins')->cascadeOnUpdate()->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unit_bisnis');
    }
};
