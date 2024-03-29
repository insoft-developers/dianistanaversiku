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
        Schema::create('sistem_ticketing', function (Blueprint $table) {
            $table->id();

            // postingan awal user atau admin
            $table->unsignedInteger('id_kategori')->index()->nullable();
            $table->foreign('id_kategori')->references('id')->on('kategori_ticketing')->cascadeOnUpdate()->nullOnDelete();

            $table->enum("send_to",["users","admin"]);

            // untuk user send to admin
            $table->unsignedBigInteger('id_user')->index()->nullable();
            $table->foreign('id_user')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            
            // untuk admin send to user atau send to banyak user
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
        Schema::dropIfExists('sistem_ticketing');
    }
};
