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
        Schema::create('aktivasi_tahunan', function (Blueprint $table) {
            $table->string('id_aktivasi_tahunan')->primary();
            $table->string('id_pegawai')->index('id_pegawai');
            $table->string('id_member')->index('id_member');
            $table->date('tanggal');
            $table->date('masa_aktif');
            $table->float('aktivasi_tahunan', 10, 0);
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
        Schema::dropIfExists('aktivasi_tahunan');
    }
};
