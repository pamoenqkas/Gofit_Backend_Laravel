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
        Schema::create('kinerja_instruktur_bulanan', function (Blueprint $table) {
            $table->string('id_kinerja_instruktur_bulanan')->primary();
            $table->date('bulan');
            $table->date('tahun');
            $table->date('tanggal_cetak');
            $table->string('nama')->nullable();
            $table->float('jumlah_hadir', 10, 0)->nullable();
            $table->float('jumlah_libur', 10, 0)->nullable();
            $table->float('jumlah_terlambat', 10, 0)->nullable();
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
        Schema::dropIfExists('kinerja_instruktur_bulanan');
    }
};
