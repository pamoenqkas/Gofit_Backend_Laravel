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
        Schema::table('presensi_kelas', function (Blueprint $table) {
            $table->foreign(['id_member'], 'presensi_kelas_ibfk_2')->references(['id_member'])->on('member');
            $table->foreign(['id_booking_kelas'], 'presensi_kelas_ibfk_1')->references(['id_booking_kelas'])->on('booking_kelas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('presensi_kelas', function (Blueprint $table) {
            $table->dropForeign('presensi_kelas_ibfk_2');
            $table->dropForeign('presensi_kelas_ibfk_1');
        });
    }
};
