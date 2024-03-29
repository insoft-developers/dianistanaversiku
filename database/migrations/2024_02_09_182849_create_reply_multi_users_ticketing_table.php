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
        Schema::create('reply_multi_users_ticketing', function (Blueprint $table) {
            $table->id();

            // ini untuk multi users di sistem_ticketing postingan awal..
            $table->unsignedBigInteger('id_ticketing')->index()->nullable();
            $table->foreign('id_ticketing')->references('id')->on('sistem_ticketing')->cascadeOnUpdate()->nullOnDelete();

            $table->unsignedBigInteger('id_user')->index()->nullable();
            $table->foreign('id_user')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();

            // untuk users ya...
            $table->enum("status_read",["no","yes"])->default("no");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reply_multi_users_ticketing');
    }
};
