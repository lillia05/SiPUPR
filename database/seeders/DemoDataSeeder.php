<?php

namespace Database\Seeders;

use App\Models\Nasabah;
use App\Models\PengajuanRek;
use App\Models\StatusLog;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $cabang = User::create([
            'username' => 'cabang',
            'email' => 'cabang@bsi.com',
            'password' => Hash::make('12345678'),
            'role' => 'cabang',
            'email_verified_at' => now(),
            'status' => 'active', // Pastikan status aktif
        ]);

        $pupr = User::create([
            'username' => 'pupr',
            'email' => 'pupr@bsi.com',
            'password' => Hash::make('12345678'),
            'role' => 'pupr',
            'email_verified_at' => now(), 
            'status' => 'active', // Pastikan status aktif
        ]);
    }
        
}