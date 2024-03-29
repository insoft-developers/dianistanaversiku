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
        Schema::create('setting_config', function (Blueprint $table) {
            $table->increments('id');

            $table->integer("iuran_bulanan");
            $table->integer("iuran_bulanan_denda");

            $table->string("wa_api_url");
            $table->string("wa_api_key");
            $table->string("wa_api_phone_no");


            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_config');
    }
};
