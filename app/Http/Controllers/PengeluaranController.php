<?php

namespace App\Http\Controllers;


use App\Models\KasbonModel;
use App\Models\Netnet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\PengeluaranModel;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengeluaranController extends Controller
{

    public function home()
    {

        $pengeluaran = PengeluaranModel::all();

        // Kirim data ke view
        return view('pengeluaran.index', compact('pengeluaran'));
    }


    public function detail($id)
    {
        $pengeluaran = PengeluaranModel::findOrFail($id);
        return view('pengeluaran.detail', compact('pengeluaran'));
    }


    public function index(Request $request)
    {
        $query = PengeluaranModel::query();

        if ($request->has('search')) {
            $query->where('nama', 'LIKE', '%' . $request->search . '%')
                ->orWhere('alamat', 'LIKE', '%' . $request->search . '%')
                ->orWhere('posisi', 'LIKE', '%' . $request->search . '%');
        }

        $pengeluaran = $query->get();


        return view('pengeluaran.index', compact('pengeluaran'));
    }

    public function create()
    {


        return view('pengeluaran.create');
    }



    public function store(Request $request)
    {

        $pengeluaran = new PengeluaranModel();

        // Isi data pengeluaran
        $pengeluaran->keterangan = $request->keterangan; // Nama dari form input
        $pengeluaran->jumlah = $request->jumlah;
        $pengeluaran->keterangan = $request->keterangan;

        // Simpan data pengeluaran ke database
        $pengeluaran->save();

        // Redirect ke halaman pengeluaran index setelah penyimpanan berhasil
        return redirect()->route('pengeluaran.index')->with('success', 'Data pengeluaran berhasil disimpan.');
    }



    public function show(string $id) {

    }


    public function edit(string $id_plg)
    {
        $pengeluaran = PengeluaranModel::findOrFail($id_plg);
        return view('pengeluaran.edit', compact('pengeluaran'));
    }


    public function update(Request $request, string $id_plg)
    {
        $pengeluaran = PengeluaranModel::findOrFail($id_plg);

        $pengeluaran->keterangan = $request->keterangan;
        $pengeluaran->jumlah = $request->jumlah;
        $pengeluaran->keterangan = $request->keterangan;

        $pengeluaran->save();

        return redirect()->route('pengeluaran.index');
    }


    public function destroy(string $id_plg)
    {
        $pengeluaran = PengeluaranModel::findOrFail($id_plg);
        $pengeluaran->delete();

        return redirect()->route('pengeluaran.index');
    }
}
