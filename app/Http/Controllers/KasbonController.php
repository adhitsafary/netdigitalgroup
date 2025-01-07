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

class KasbonController extends Controller
{

    public function home()
    {

        $kasbon = KasbonModel::all();

        // Hitung total pendapatan bulanan
        $totalPendapatanBulanan = $kasbon->sum('harga_paket');

        // Hitung total jumlah pengguna
        $totalJumlahPengguna = $kasbon->count();

        // Kirim data ke view
        return view('karyawan.kasbon.index', compact('kasbon', 'totalPendapatanBulanan', 'totalJumlahPengguna'));
    }


    public function detail($id)
    {
        $kasbon = KasbonModel::findOrFail($id);
        return view('kasbon.detail', compact('kasbon'));
    }


    public function index(Request $request)
    {
        $query = KasbonModel::query();

        if ($request->has('search')) {
            $query->where('nama', 'LIKE', '%' . $request->search . '%')
                ->orWhere('alamat', 'LIKE', '%' . $request->search . '%')
                ->orWhere('posisi', 'LIKE', '%' . $request->search . '%');
        }

        $kasbon = $query->get();


        return view('karyawan.kasbon.index', compact('kasbon'));
    }

    public function create($id)
    {
        // Cari karyawan berdasarkan ID yang diterima dari URL
        $karyawan = KaryawanModel::findOrFail($id);

        // Kirim data karyawan ke view
        return view('karyawan.kasbon.create', compact('karyawan'));    }


    public function store(Request $request)
    {
        // Ambil id_karyawan dari input hidden di form
        $id_karyawan = $request->input('id_karyawan');

        // Cari karyawan berdasarkan id_karyawan
        $karyawan = KaryawanModel::findOrFail($id_karyawan);

        // Membuat instance baru dari model Kasbon
        $kasbon = new KasbonModel();

        // Isi data kasbon
        $kasbon->id_karyawan = $karyawan->id;
        $kasbon->nama = $request->nama; // Nama dari form input
        $kasbon->jumlah = $request->jumlah;
        $kasbon->tanggal = $request->tanggal;
        $kasbon->keterangan = $request->keterangan;

        // Simpan data kasbon ke database
        $kasbon->save();

        // Redirect ke halaman kasbon index setelah penyimpanan berhasil
        return redirect()->route('kasbon.index')->with('success', 'Data kasbon berhasil disimpan.');
    }



    public function show(string $id) {}


    public function edit(string $id_plg)
    {
        $kasbon = KasbonModel::findOrFail($id_plg);
        return view('karyawan.kasbon.edit', compact('kasbon'));
    }


    public function update(Request $request, string $id_plg)
    {
        $kasbon = KasbonModel::findOrFail($id_plg);

        $kasbon->nama = $request->nama;
        $kasbon->jumlah = $request->jumlah;
        $kasbon->tanggal = $request->tanggal;
        $kasbon->keterangan = $request->keterangan;

        $kasbon->save();

        return redirect()->route('kasbon.index');
    }


    public function destroy(string $id_plg)
    {
        $kasbon = KasbonModel::findOrFail($id_plg);
        $kasbon->delete();

        return redirect()->route('kasbon.index');
    }
}
