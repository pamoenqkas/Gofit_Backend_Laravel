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
        Schema::create('deposit_umum', function (Blueprint $table) {
            $table->string('id_deposit_umum')->primary();
            $table->string('id_pegawai')->index('id_pegawai');
            $table->string('id_member')->index('id_member');
            $table->string('id_promo_umum')->index('id_promo_umum');
            $table->date('tanggal');
            $table->float('deposit', 10, 0)->nullable();
            $table->float('total_deposit', 10, 0)->nullable();
            $table->float('bonus_deposit', 10, 0)->nullable();
            $table->float('sisa_deposit', 10, 0)->nullable();
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
        Schema::dropIfExists('deposit_umum');
    }
};
