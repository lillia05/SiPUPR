<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use App\Models\PenerimaBantuan; 
use App\Models\TahapanPenyaluran; 
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class PenerimaBantuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $funding = User::create([
            'username' => 'bsi',
            'password' => Hash::make('12345678'),
            'role' => 'Cabang',
        ]);

        $admin = User::create([
            'username' => 'user',
            'password' => Hash::make('12345678'),
            'role' => 'User',
        ]);

        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 50; $i++) {
            
            $penerima = PenerimaBantuan::create([
                'nama_pb'        => $faker->name,
                'nomor_rekening' => '73220' . $faker->unique()->numerify('#####'), 
                'desa'           => $faker->streetName, 
                'kecamatan'      => $faker->citySuffix, 
                'kabupaten'      => $faker->city,
                'total_alokasi_bantuan' => 20000000, 
            ]);

            $progressLevel = rand(0, 3); 

            TahapanPenyaluran::create([
                'penerima_bantuan_id' => $penerima->id,
                'tahap_ke'            => 1,
                'nominal'             => 10000000,
                'status'              => $progressLevel >= 1 ? 'DONE' : 'not',
                'tanggal_transaksi'   => $progressLevel >= 1 ? $faker->dateTimeBetween('-4 months', '-3 months') : null,
            ]);

            TahapanPenyaluran::create([
                'penerima_bantuan_id' => $penerima->id,
                'tahap_ke'            => 2,
                'nominal'             => 7500000,
                'status'              => $progressLevel >= 2 ? 'DONE' : 'not',
                'tanggal_transaksi'   => $progressLevel >= 2 ? $faker->dateTimeBetween('-3 months', '-2 months') : null,
            ]);

            TahapanPenyaluran::create([
                'penerima_bantuan_id' => $penerima->id,
                'tahap_ke'            => 3,
                'nominal'             => 2500000,
                'status'              => $progressLevel >= 3 ? 'DONE' : 'not',
                'tanggal_transaksi'   => $progressLevel >= 3 ? $faker->dateTimeBetween('-1 month', 'now') : null,
            ]);
        }
    }
}