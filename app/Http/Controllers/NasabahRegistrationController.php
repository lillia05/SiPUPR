<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use App\Models\User;
use App\Models\PengajuanRek;
use App\Models\PekerjaanNasabah;
use App\Models\StatusLog;
use App\Notifications\NasabahVerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;

class NasabahRegistrationController extends Controller
{
    // 1. Tampilkan Form (Public)
    public function create()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('nasabah.dashboard');
    }

    // 2. Proses Simpan & Registrasi (Public)
    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            // User Data
            'username' => 'required|string|max:255|unique:users,username',
            'email'    => 'required|email|unique:users,email',
            
            // Nasabah Data
            'jenis_produk'      => 'required',
            'nik_ktp'           => 'required|numeric|digits:16|unique:nasabah,nik_ktp',
            'npwp'              => 'nullable|numeric',
            'no_hp'             => 'required|numeric',
            'tempat_lahir'      => 'required',
            'tanggal_lahir'     => 'required|date',
            'nama_ibu'          => 'required',
            'alamat'            => 'required',
            'status_pernikahan' => 'required',
            'area_kerja'        => 'required',
            'jabatan'           => 'required',
            'nama_keluarga'     => 'required',
            'hp_keluarga'       => 'required',
            'alamat_keluarga'   => 'required',
            'foto_ktp'          => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'foto_npwp'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::beginTransaction();

            // A. Upload File
            $pathKtp = $request->file('foto_ktp')->store('dokumen_nasabah', 'public');
            $pathNpwp = null;
            if ($request->hasFile('foto_npwp')) {
                $pathNpwp = $request->file('foto_npwp')->store('dokumen_nasabah', 'public');
            }

            // B. Buat User Baru (Register Otomatis)
            $user = User::create([
                'username' => $request->username,
                'email'    => $request->email,
                'password' => Hash::make('12345678'), // Password Default
                'role'     => 'Nasabah',
                'status'   => 'active',
            ]);

            // C. Simpan Data Nasabah
            $nasabah = Nasabah::create([
                'user_id'           => $user->id,
                'nik_ktp'           => $request->nik_ktp,
                'npwp'              => $request->npwp,
                'tempat_lahir'      => $request->tempat_lahir,
                'tanggal_lahir'     => $request->tanggal_lahir,
                'no_hp'             => $request->no_hp,
                'alamat'            => $request->alamat,
                'kode_pos'          => $request->kode_pos,
                'nama_ibu'          => $request->nama_ibu,
                'status_pernikahan' => $request->status_pernikahan,
                'rek_bsi_lama'      => $request->rek_bsi_lama,
                'nama_keluarga_tidak_serumah'   => $request->nama_keluarga,
                'alamat_keluarga_tidak_serumah' => $request->alamat_keluarga,
                'no_hp_keluarga_tidak_serumah'  => $request->hp_keluarga,
                'foto_ktp'          => $pathKtp,
                'foto_npwp'         => $pathNpwp,
            ]);

            // D. Simpan Pekerjaan
            PekerjaanNasabah::create([
                'nasabah_id' => $nasabah->id,
                'area_kerja' => $request->area_kerja,
                'jabatan'    => $request->jabatan,
            ]);

            // E. Simpan Pengajuan
            $pengajuan = PengajuanRek::create([
                'nasabah_id'    => $nasabah->id,
                'jenis_produk'  => $request->jenis_produk,
                'status'        => 'draft',
                'tanggal_input' => now(),
            ]);

            // F. Log Status
            StatusLog::create([
                'pengajuan_id' => $pengajuan->id,
                'user_id'      => $user->id,
                'status_lama'  => null,
                'status_baru'  => 'draft',
                'catatan'      => 'Pendaftaran mandiri via website.',
            ]);

            DB::commit();

            // G. PROSES SETELAH SUKSES
            
            // 1. Kirim Email Verifikasi 
            $user->notify(new NasabahVerifyEmail); 

            // 2. Login Otomatis
            Auth::login($user);

            // 3. Lempar ke Halaman "Silakan Cek Email"
            return redirect()->route('verification.notice');


        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($pathKtp)) Storage::disk('public')->delete($pathKtp);
            if (isset($pathNpwp)) Storage::disk('public')->delete($pathNpwp);
            
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
}