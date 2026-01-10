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
        Schema::create('pengajuan_rek', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('nasabah')->onDelete('cascade');
            $table->enum('jenis_produk', ['Payroll Wadiah','Easy Wadiah','Easy Mudharabah', 'Haji', 'Tapenas'])->default('Payroll Wadiah');
            $table->string('no_rek')->nullable(); 
            $table->enum('status', ['draft', 'process', 'ready', 'done'])->default('draft'); 
            $table->timestamp('tanggal_input')->useCurrent(); 
            $table->timestamp('tanggal_serah_terima')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_rek');
    }
};
