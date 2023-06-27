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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->string('id_pegawai')->primary();
            $table->string('id_role')->index('id_role');
            $table->string('nama_pegawai');
            $table->string('no_telp_pegawai');
            $table->string('alamat_pegawai');
            $table->string('email_pegawai');
            $table->date('tanggal_lahir_pegawai');
            $table->string('password');
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
        Schema::dropIfExists('pegawai');
    }
};
