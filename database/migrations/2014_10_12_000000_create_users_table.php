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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique()->nullable(); 
            $table->string('password'); 
<<<<<<< HEAD
            $table->enum('role', ['cabang', 'pupr'])->default('pupr'); 
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('avatar')->nullable();
            $table->rememberToken();
=======
>>>>>>> 5e476deaaf412f725062aec88d329fe46c149a8a
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
