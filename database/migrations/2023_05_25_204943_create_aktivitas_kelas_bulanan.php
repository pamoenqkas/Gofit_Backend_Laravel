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
        Schema::create('aktivitas_kelas_bulanan', function (Blueprint $table) {
            $table->string('id_aktivitas_kelas_bulanan')->primary();
            $table->date('bulan');
            $table->date('tahun');
            $table->date('tanggal_cetak');
            $table->string('kelas')->nullable();
            $table->float('instruktur', 10, 0)->nullable();
            $table->float('jumlah_peserta', 10, 0)->nullable();
            $table->float('jumlah_libur', 10, 0)->nullable();
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
        Schema::dropIfExists('aktivitas_kelas_bulanan');
    }
};
