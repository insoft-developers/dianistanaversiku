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
        Schema::create('reply_admin_user_ticketing', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_ticketing')->index()->nullable();
            $table->foreign('id_ticketing')->references('id')->on('sistem_ticketing')->cascadeOnUpdate()->nullOnDelete();

            $table->enum("type_msg",["reply_user","send_users"]);
            
            $table->unsignedInteger('id_admin')->index()->nullable();
            $table->foreign('id_admin')->references('id')->on('admins')->cascadeOnUpdate()->nullOnDelete();

            $table->mediumText("message");
            $table->enum("type_file",["image","pdf","word","excel"]);
            $table->string("file_name");

            // untuk type_msg = reply_user
            $table->enum("status_read",["no","yes"])->nullable();

            // ini untuk admin reply user == jika id_ticketing status send_to == users ...
            $table->unsignedBigInteger('id_user')->index()->nullable();
            $table->foreign('id_user')->references('id')->on('users')->cascadeOnUpdate()->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reply_admin_user_ticketing');
    }
};
