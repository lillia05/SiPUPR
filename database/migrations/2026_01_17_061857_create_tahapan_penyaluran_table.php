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
        Schema::create('tahapan_penyaluran', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('penerima_bantuan_id')
                ->constrained('penerima_bantuan')
                ->onDelete('cascade'); 

            $table->integer('tahap_ke'); 

            $table->decimal('nominal', 15, 2);

            $table->enum('status', ['DONE', 'not'])->default('not'); 


            $table->date('tanggal_transaksi')->nullable();

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahapan_penyaluran');
    }
};
