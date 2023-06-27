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
        Schema::create('member', function (Blueprint $table) {
            $table->string('id_member')->primary();
            $table->string('nama_member');
            $table->string('no_telp_member');
            $table->string('alamat_member');
            $table->string('email_member');
            $table->date('tanggal_lahir');
            $table->float('deposit', 10, 0);
            $table->float('deposit_kelas', 10, 0);
            $table->date('masa_membership')->nullable();
            $table->date('tanggal_daftar');
            $table->string('status');
            $table->string('password');
            $table->date('masa_berlaku_kelas')->nullable();
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
        Schema::dropIfExists('member');
    }
};
