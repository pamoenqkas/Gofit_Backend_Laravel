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
        Schema::create('aktivitas_gym_bulanan', function (Blueprint $table) {
            $table->string('id_aktivitas_gym_bulanan')->primary();
            $table->date('bulan');
            $table->date('tahun');
            $table->date('tanggal_cetak');
            $table->date('tanggal')->nullable();
            $table->float('jumlah_member', 10, 0)->nullable();
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
        Schema::dropIfExists('aktivitas_gym_bulanan');
    }
};
