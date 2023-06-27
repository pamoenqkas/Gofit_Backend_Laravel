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
        Schema::create('presensi_gym', function (Blueprint $table) {
            $table->string('id_presensi_gym')->primary();
            $table->string('id_member')->index('id_member');
            $table->string('id_booking_gym')->index('id_booking_gym');
            $table->string('status');
            $table->dateTime('tanggal_presensi_gym');
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
        Schema::dropIfExists('presensi_gym');
    }
};
