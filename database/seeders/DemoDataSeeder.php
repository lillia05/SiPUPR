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
            'password' => Hash::make('12345678'),
            'role' => 'cabang',
        ]);

        $pupr = User::create([
            'username' => 'pupr',
            'password' => Hash::make('12345678'),
            'role' => 'pupr',
        ]);
    }
        
}