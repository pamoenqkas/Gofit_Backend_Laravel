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
        Schema::create('presensi_kelas', function (Blueprint $table) {
            $table->string('id_presensi_kelas')->primary();
            $table->string('id_booking_kelas')->index('id_booking_kelas');
            $table->string('id_member')->index('id_member');
            $table->dateTime('tanggal_presensi_kelas');
            $table->string('status');
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
        Schema::dropIfExists('presensi_kelas');
    }
};
