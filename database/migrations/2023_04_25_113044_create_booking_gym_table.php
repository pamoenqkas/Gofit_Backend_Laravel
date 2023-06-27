<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_gym', function (Blueprint $table) {
            $table->string('id_booking_gym')->primary();
            $table->string('id_member')->index('id_member');
            $table->string('id_sesi')->index('id_sesi');
            $table->dateTime('tanggal_booking_gym');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_gym');
    }
};
