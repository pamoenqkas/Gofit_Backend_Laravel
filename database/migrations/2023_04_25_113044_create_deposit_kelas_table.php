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
        Schema::create('deposit_kelas', function (Blueprint $table) {
            $table->string('id_deposit_kelas')->primary();
            $table->string('id_member')->index('id_member');
            $table->string('id_pegawai')->index('id_pegawai');
            $table->string('id_promo_kelas')->index('id_promo_kelas');
            $table->date('tanggal');
            $table->float('deposit_kelas', 10, 0);
            $table->string('jenis_kelas');
            $table->float('total_deposit', 10, 0);
            $table->date('masa_berlaku')->nullable();
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
        Schema::dropIfExists('deposit_kelas');
    }
};
