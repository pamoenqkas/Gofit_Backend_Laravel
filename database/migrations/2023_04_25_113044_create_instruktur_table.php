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
        Schema::create('instruktur', function (Blueprint $table) {
            $table->string('id_instruktur')->primary();
            $table->string('nama_instruktur');
            $table->string('no_telp_instruktur');
            $table->string('alamat_instruktur');
            $table->string('email_instruktur');
            $table->date('tanggal_lahir_instruktur');
            $table->string('password');
            $table->time('total_terlambat');
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
        Schema::dropIfExists('instruktur');
    }
};
