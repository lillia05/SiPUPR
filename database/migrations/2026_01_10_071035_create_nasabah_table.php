<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('nasabah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); 
            $table->string('nik_ktp', 16)->unique();
            $table->string('npwp')->nullable();
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->text('alamat');
            $table->string('kode_pos');
            $table->string('status_pernikahan');
            $table->string('no_hp');
            $table->string('nama_ibu');
            $table->string('rek_bsi_lama')->nullable();
            $table->string('nama_keluarga_tidak_serumah');
            $table->text('alamat_keluarga_tidak_serumah');
            $table->string('no_hp_keluarga_tidak_serumah');
            $table->string('foto_ktp')->nullable();
            $table->string('foto_npwp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nasabah');
    }
};
