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
        Schema::create('banner_iklan', function (Blueprint $table) {
            $table->increments('id');

            $table->string("title");
            $table->string("slug_title")->nullable();
            $table->string("image")->nullable();
            $table->string("link_terkait")->nullable();
            
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_iklan');
    }
};
