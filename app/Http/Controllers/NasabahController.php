<?php

namespace App\Http\Controllers;

use App\Exports\NasabahExport;
use App\Imports\NasabahImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Nasabah;
use App\Models\User;
use App\Models\PengajuanRek;
use App\Models\PekerjaanNasabah; 
use App\Models\StatusLog;        
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage; 

class NasabahController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query dengan Eager Loading
        $query = Nasabah::with(['user', 'pengajuan']);

        // 2. Logic Searching
        if ($request->filled('search')) {
            $search = $request->input('search'); // Gunakan input() lebih aman
            $query->where(function($q) use ($search) {
                $q->where('nik_ktp', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%") // Cari berdasarkan Name juga
                                ->orWhere('username', 'like', "%{$search}%");
                  })
                  ->orWhereHas('pengajuan', function($pengajuanQuery) use ($search) {
                      $pengajuanQuery->where('no_rek', 'like', "%{$search}%"); 
                  });
            });
        }

        // 3. Logic Filter Produk
        if ($request->filled('produk')) {
            $produk = $request->input('produk');
            $query->whereHas('pengajuan', function($q) use ($produk) {
                $q->where('jenis_produk', $produk);
            });
        }

        // 4. Pagination
        $perPage = $request->input('per_page', 10);
        $nasabah = $query->latest()->paginate($perPage);
        
        // Penting: Append query string agar pagination tidak reset saat ganti halaman
        $nasabah->appends($request->all());

        // 5. [FIX UTAMA] Tentukan View Berdasarkan Role User
        // Agar tidak hardcoded ke 'funding.nasabah.index'
        $prefix = strtolower(auth()->user()->role); // Hasil: 'cabang' atau 'funding'
        
        // Pastikan Anda memiliki folder view: 
        // resources/views/cabang/nasabah/index.blade.php
        // resources/views/funding/nasabah/index.blade.php
        return view($prefix . '.nasabah.index', compact('nasabah'));
    }

    public function create()
    {
        // Sesuaikan juga view create agar dinamis
        $prefix = strtolower(auth()->user()->role);
        return view($prefix . '.nasabah.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'jenis_produk' => 'required',
            'nik_ktp' => 'required|numeric|digits:16|unique:nasabah,nik_ktp',
            'npwp' => 'nullable|string', 
            'no_hp' => 'required|numeric',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'nama_ibu' => 'required',
            'alamat' => 'required',
            'status_pernikahan' => 'required',
            'area_kerja' => 'required',
            'jabatan' => 'required',
            'nama_keluarga' => 'required',
            'hp_keluarga' => 'required',
            'alamat_keluarga' => 'required',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_npwp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request) {
                
                $pathKtp = null;
                $pathNpwp = null;

                if ($request->hasFile('foto_ktp')) {
                    $pathKtp = $request->file('foto_ktp')->store('dokumen_nasabah', 'public');
                }

                if ($request->hasFile('foto_npwp')) {
                    $pathNpwp = $request->file('foto_npwp')->store('dokumen_nasabah', 'public');
                }

                // Create User
                $user = User::create([
                    'name' => $request->username, 
                    'username' => $request->username, 
                    'email' => $request->email,
                    'password' => Hash::make('12345678'), 
                    'role' => 'Nasabah',
                    'email_verified_at' => now(),
                ]);

                // Create Nasabah
                $nasabah = Nasabah::create([
                    'user_id' => $user->id,
                    'nik_ktp' => $request->nik_ktp,
                    'npwp' => $request->npwp,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'kode_pos' => $request->kode_pos ?? null, // Handle null jika tidak ada di form
                    'nama_ibu' => $request->nama_ibu,
                    'status_pernikahan' => $request->status_pernikahan,
                    'rek_bsi_lama' => $request->rek_bsi_lama ?? null,
                    'nama_keluarga_tidak_serumah' => $request->nama_keluarga,
                    'alamat_keluarga_tidak_serumah' => $request->alamat_keluarga,
                    'no_hp_keluarga_tidak_serumah' => $request->hp_keluarga,
                    'foto_ktp' => $pathKtp,
                    'foto_npwp' => $pathNpwp,
                ]);

                // Create Pekerjaan
                PekerjaanNasabah::create([
                    'nasabah_id' => $nasabah->id,
                    'area_kerja' => $request->area_kerja,
                    'jabatan' => $request->jabatan,
                ]);

                // Create Pengajuan
                $pengajuan = PengajuanRek::create([
                    'nasabah_id' => $nasabah->id,
                    'jenis_produk' => $request->jenis_produk,
                    'status' => 'draft',
                    'tanggal_input' => now(),
                ]);

                // Log Status
                StatusLog::create([
                    'pengajuan_id' => $pengajuan->id,
                    'user_id' => auth()->id(), 
                    'status_lama' => null,
                    'status_baru' => 'draft',
                    'catatan' => 'Input data baru oleh ' . auth()->user()->role,
                ]);
            });

            // Redirect Dinamis
            $prefix = strtolower(auth()->user()->role); 
            return redirect()->route($prefix . '.nasabah.index')->with('success', 'Data nasabah berhasil disimpan!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $nasabah = Nasabah::with(['user', 'pekerjaan', 'pengajuan'])->findOrFail($id);
        $prefix = strtolower(auth()->user()->role);
        return view($prefix . '.nasabah.show', compact('nasabah'));
    }

    public function edit($id)
    {
        $nasabah = Nasabah::with(['user', 'pekerjaan', 'pengajuan'])->findOrFail($id);
        $prefix = strtolower(auth()->user()->role);
        return view($prefix . '.nasabah.edit', compact('nasabah'));
    }

    public function update(Request $request, $id)
    {
        $nasabah = Nasabah::findOrFail($id);

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $nasabah->user_id,
            'jenis_produk' => 'required',
            'nik_ktp' => 'required|numeric|digits:16|unique:nasabah,nik_ktp,' . $id,
            'npwp' => 'nullable|string',
            'no_hp' => 'required|numeric',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required|date',
            'nama_ibu' => 'required',
            'alamat' => 'required',
            'status_pernikahan' => 'required',
            'area_kerja' => 'required',
            'jabatan' => 'required',
            'nama_keluarga' => 'required',
            'hp_keluarga' => 'required',
            'alamat_keluarga' => 'required',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'foto_npwp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            DB::transaction(function () use ($request, $nasabah) {
                
                $nasabah->user->update([
                    'name' => $request->username,
                    'username' => $request->username,
                    'email' => $request->email,
                ]);

                $pathKtp = $nasabah->foto_ktp; 
                if ($request->hasFile('foto_ktp')) {
                    if ($nasabah->foto_ktp && Storage::disk('public')->exists($nasabah->foto_ktp)) {
                        Storage::disk('public')->delete($nasabah->foto_ktp);
                    }
                    $pathKtp = $request->file('foto_ktp')->store('dokumen_nasabah', 'public');
                }

                $pathNpwp = $nasabah->foto_npwp;
                if ($request->hasFile('foto_npwp')) {
                    if ($nasabah->foto_npwp && Storage::disk('public')->exists($nasabah->foto_npwp)) {
                        Storage::disk('public')->delete($nasabah->foto_npwp);
                    }
                    $pathNpwp = $request->file('foto_npwp')->store('dokumen_nasabah', 'public');
                }

                $nasabah->update([
                    'nik_ktp' => $request->nik_ktp,
                    'npwp' => $request->npwp,
                    'tempat_lahir' => $request->tempat_lahir,
                    'tanggal_lahir' => $request->tanggal_lahir,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'kode_pos' => $request->kode_pos ?? $nasabah->kode_pos,
                    'nama_ibu' => $request->nama_ibu,
                    'status_pernikahan' => $request->status_pernikahan,
                    'rek_bsi_lama' => $request->rek_bsi_lama,
                    'nama_keluarga_tidak_serumah' => $request->nama_keluarga,
                    'alamat_keluarga_tidak_serumah' => $request->alamat_keluarga,
                    'no_hp_keluarga_tidak_serumah' => $request->hp_keluarga,
                    'foto_ktp' => $pathKtp,
                    'foto_npwp' => $pathNpwp,
                ]);

                $nasabah->pekerjaan()->updateOrCreate(
                    ['nasabah_id' => $nasabah->id],
                    [
                        'area_kerja' => $request->area_kerja,
                        'jabatan' => $request->jabatan
                    ]
                );

                $pengajuanTerakhir = $nasabah->pengajuan()->latest()->first();
                if ($pengajuanTerakhir && $pengajuanTerakhir->status == 'draft') {
                    $pengajuanTerakhir->update(['jenis_produk' => $request->jenis_produk]);
                }
            });

            $prefix = strtolower(auth()->user()->role);
            return redirect()->route($prefix . '.nasabah.index')->with('success', 'Data nasabah berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal update: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        $nasabah = Nasabah::findOrFail($id);
        
        if ($nasabah->foto_ktp && Storage::disk('public')->exists($nasabah->foto_ktp)) {
            Storage::disk('public')->delete($nasabah->foto_ktp);
        }
        if ($nasabah->foto_npwp && Storage::disk('public')->exists($nasabah->foto_npwp)) {
            Storage::disk('public')->delete($nasabah->foto_npwp);
        }

        $nasabah->user->delete(); 
        
        return redirect()->back()->with('success', 'Data nasabah telah dihapus.');
    }

    public function export() 
    {
        return Excel::download(new NasabahExport, 'data_nasabah_' . date('d-m-Y_H-i') . '.xlsx');
    }

    public function import(Request $request) 
    {
        $request->validate([
            'file_excel' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new NasabahImport, $request->file('file_excel'));
            return redirect()->back()->with('success', 'Data nasabah berhasil diimport!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }
}