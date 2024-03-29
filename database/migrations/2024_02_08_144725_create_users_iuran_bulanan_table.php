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
        Schema::create('users_iuran_bulanan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user')->index()->nullable();
            $table->foreign('id_user')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();
            $table->integer("nominal_iuran")->nullable();
            $table->integer("denda_iuran")->nullable();
            $table->enum("status_bayar",["Tidak","Ya"])->default("Tidak");
            $table->enum("jenis_pembayaran",["otomatis","tunai"])->nullable();

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
        Schema::dropIfExists('users_iuran_bulanan');
    }
};
