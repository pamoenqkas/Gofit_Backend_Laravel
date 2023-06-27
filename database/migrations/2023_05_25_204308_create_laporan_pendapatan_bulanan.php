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
        Schema::create('laporan_pendapatan_bulanan', function (Blueprint $table) {
            $table->string('id_laporan_pendapatan_bulanan')->primary();
            $table->date('periode');
            $table->date('tanggal_cetak')->nullable();
            $table->date('bulan')->nullable();
            $table->float('aktivasi', 10, 0)->nullable();
            $table->float('deposit', 10, 0)->nullable();
            $table->float('total', 10, 0)->nullable();
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
        Schema::dropIfExists('laporan_pendapatan_bulanan');
    }
};
