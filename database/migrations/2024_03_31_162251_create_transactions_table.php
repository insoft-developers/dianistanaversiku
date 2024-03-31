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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('business_unit_id');
            $table->string('invoice');
            $table->string('start_time');
            $table->string('finish_time');
            $table->integer('quantity');
            $table->integer('total_price');
            $table->date('booking_date');
            $table->text('description');
            $table->string('payment_status');
            $table->string('payment_link');
            $table->string('payment_method');
            $table->string('payment_channel');
            $table->date('paid_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
