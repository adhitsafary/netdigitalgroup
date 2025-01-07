<?php

namespace App\Http\Controllers;


use App\Models\KasbonModel;
use App\Models\Netnet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\PemasukanModel;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PemasukanController extends Controller
{

    public function home()
    {

        $pemasukan = PemasukanModel::all();

        // Kirim data ke view
        return view('pemasukan.index', compact('pemasukan'));
    }


    public function detail($id)
    {
        $pemasukan = PemasukanModel::findOrFail($id);
        return view('pemasukan.detail', compact('pemasukan'));
    }


    public function index(Request $request)
    {
        $query = PemasukanModel::query();

        if ($request->has('search')) {
            $query->where('keterangan', 'LIKE', '%' . $request->search . '%');
        }

        $pemasukan = $query->get();


        return view('pemasukan.index', compact('pemasukan'));
    }

    public function create()
    {


        return view('pemasukan.create');
    }



    public function store(Request $request)
    {

        $pemasukan = new PemasukanModel();

        // Isi data pemasukan
        $pemasukan->jumlah = $request->jumlah;
        $pemasukan->keterangan = $request->keterangan;

        // Simpan data pemasukan ke database
        $pemasukan->save();

        // Redirect ke halaman pemasukan index setelah penyimpanan berhasil
        return redirect()->route('pemasukan.index')->with('success', 'Data pemasukan berhasil disimpan.');
    }



    public function show(string $id) {

    }


    public function edit(string $id)
    {
        $pemasukan = PemasukanModel::findOrFail($id);
        return view('pemasukan.edit', compact('pemasukan'));
    }


    public function update(Request $request, string $id)
    {
        $pemasukan = PemasukanModel::findOrFail($id);

        $pemasukan->jumlah = $request->jumlah;
        $pemasukan->keterangan = $request->keterangan;

        $pemasukan->save();

        return redirect()->route('pemasukan.index');
    }


    public function destroy(string $id)
    {
        $pemasukan = PemasukanModel::findOrFail($id);
        $pemasukan->delete();

        return redirect()->route('pemasukan.index');
    }
}
