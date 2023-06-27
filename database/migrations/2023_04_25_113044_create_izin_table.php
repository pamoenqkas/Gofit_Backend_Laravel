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
        Schema::create('izin', function (Blueprint $table) {
            $table->string('id_izin')->primary();
            $table->string('id_instruktur')->index('id_instruktur');
            $table->string('id_jadwal_harian')->nullable()->index('izin_ibfk_2');
            $table->string('id_instruktur_pengganti')->nullable()->index('id_instruktur_pengganti');
            $table->date('tanggal');
            $table->string('deskripsi_izin');
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
        Schema::dropIfExists('izin');
    }
};
