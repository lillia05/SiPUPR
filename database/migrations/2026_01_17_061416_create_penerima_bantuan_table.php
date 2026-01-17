<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penerima_bantuan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pb');
            $table->string('nomor_rekening')->unique(); 
            $table->string('desa');
            $table->string('kecamatan');
            $table->string('kabupaten');
            $table->string('deliniasi');
            $table->decimal('total_alokasi_bantuan', 15, 2)->default(20000000); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerima_bantuan');
    }
};
