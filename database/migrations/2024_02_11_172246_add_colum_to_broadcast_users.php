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
        Schema::table('broadcast_users', function (Blueprint $table) {

            $table->unsignedBigInteger('id_broadcast')->after("id")->index()->nullable();
            $table->foreign('id_broadcast')->references('id')->on('broadcast')->cascadeOnUpdate()->cascadeOnDelete();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('broadcast_users', function (Blueprint $table) {
            $table->dropForeign(["id_broadcast"]);
            $table->dropIndex(["id_broadcast"]);
            $table->dropColumn("id_broadcast");
        });
    }
};
