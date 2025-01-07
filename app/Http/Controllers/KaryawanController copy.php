<?php

namespace App\Http\Controllers;

use App\Models\KaryawanModel;
use App\Models\KasbonModel;
use App\Models\Netnet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelanggan;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KaryawanController extends Controller
{





    public function detail($id)
    {
        // Cari karyawan berdasarkan ID
        $karyawan = KaryawanModel::findOrFail($id);

        // Ambil data kasbon yang terkait dengan karyawan ini
        $kasbon = KasbonModel::where('id_karyawan', $id)->get();

        // Hitung total kasbon
        $totalKasbon = $kasbon->sum('jumlah');
        $gaji = $karyawan->sum('gaji');
        $total =  $gaji - $totalKasbon;

        // Kirim data karyawan, kasbon, dan totalKasbon ke view
        return view('karyawan.detail', compact('karyawan', 'kasbon', 'totalKasbon', 'total'));
    }



    public function index(Request $request)
    {
        $query = KaryawanModel::query();

        if ($request->has('search')) {
            $query->where('nama', 'LIKE', '%' . $request->search . '%')
                ->orWhere('alamat', 'LIKE', '%' . $request->search . '%')
                ->orWhere('posisi', 'LIKE', '%' . $request->search . '%');
        }

        $karyawan = $query->get();


        return view('karyawan.index', compact('karyawan'));
    }



    public function create()
    {
        return view('karyawan.create');
    }


    public function store(Request $request)
    {
        $karyawan = new KaryawanModel();

        $karyawan->nama = $request->nama;
        $karyawan->ktp = $request->ktp;
        $karyawan->alamat = $request->alamat;
        $karyawan->no_telepon = $request->no_telepon;
        $karyawan->posisi = $request->posisi;
        $karyawan->mulai_kerja = $request->mulai_kerja;
        $karyawan->gaji = $request->gaji;
        $karyawan->tgl_gajihan = $request->tgl_gajihan;
        $karyawan->keterangan = $request->keterangan;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $path = $file->store('public/karyawan');
            $karyawan->foto = str_replace('public/', 'storage/', $path);
        }

        $karyawan->save();

        return redirect()->route('karyawan.index');
    }

    



    public function show(string $id) {}


    public function edit(string $id_plg)
    {
        $karyawan = KaryawanModel::findOrFail($id_plg);
        return view('karyawan.edit', compact('karyawan'));
    }


    public function update(Request $request, string $id_plg)
    {
        $karyawan = KaryawanModel::findOrFail($id_plg);

        $karyawan->nama = $request->nama;
        $karyawan->alamat = $request->alamat;
        $karyawan->no_telepon = $request->no_telepon;
        $karyawan->posisi = $request->posisi;
        $karyawan->mulai_kerja = $request->mulai_kerja;
        $karyawan->gaji = $request->gaji;
        $karyawan->tgl_gajihan = $request->tgl_gajihan;
        $karyawan->keterangan = $request->keterangan;

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $path = $file->store('public/karyawan');
            $karyawan->foto = str_replace('public/', 'storage/', $path);
        }

        $karyawan->save();

        return redirect()->route('karyawan.index');
    }


    public function destroy(string $id_plg)
    {
        $karyawan = KaryawanModel::findOrFail($id_plg);
        $karyawan->delete();

        return redirect()->route('karyawan.index');
    }

    public function showOff($id)
    {

        $karyawan = KaryawanModel::find($id);

        if ($karyawan) {
            // Masukkan data ke tabel plg_of
            DB::table('karyawan_of')->insert([

                'nama' => $karyawan->nama,
                'alamat' => $karyawan->alamat,
                'no_telepon' => $karyawan->no_telepon,
                'posisi' => $karyawan->posisi,
                'mulai_kerja' => $karyawan->mulai_kerja,
                'keterangan' => $karyawan->keterangan,

                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Hapus data dari tabel karyawan
            $karyawan->delete();

            // Redirect ke halaman karyawan dengan pesan sukses
            return redirect()->route('karyawan.index')->with('success', 'karyawan berhasil dipindahkan ke tabel karyawan off.');
        } else {
            return redirect()->route('karyawan.index')->with('error', 'karyawan tidak ditemukan.');
        }
    }
}
