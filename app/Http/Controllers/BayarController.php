<?php

namespace App\Http\Controllers;

use App\Models\Netnet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\Pelangganof;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class BayarController extends Controller
{

    public function home()
    {

        $pelanggan = Pelanggan::all();
        $pelangganof = Pelangganof::all();


        // Hitung total pendapatan bulanan
        $totalPendapatanBulanan = $pelanggan->sum('harga_paket');

        // Hitung total jumlah pengguna
        $totalJumlahPengguna = $pelanggan->count();

         // Hitung total pengurangan pendapatan dari pelanggan off bulanan
        $pelangganofuang = $pelangganof->sum('harga_paket');

        // Hitung total jumlah pengguna
        $pelangganoforang = $pelangganof->count();

        $pelanggan_of = $pelangganof->count();
        $pelanggan_of_uang = $pelangganof->sum('harga_paket');

        $totalpendapatanakhir = $totalPendapatanBulanan + $pelangganofuang;
        $totaluser = $totalJumlahPengguna + $pelangganoforang ;

        // Kirim data ke view
        return view('index', compact('pelanggan', 'pelanggan_of', 'pelanggan_of_uang', 'totalpendapatanakhir', 'totaluser', 'pelangganofuang', 'pelangganoforang', 'totalPendapatanBulanan', 'totalJumlahPengguna'));
    }


    public function detail($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.detail', compact('pelanggan'));
    }





    public function index(Request $request)
    {
        $query = Pelanggan::query();

        if ($request->has('search')) {
            $query->where('nama_plg', 'LIKE', '%' . $request->search . '%')
                ->orWhere('alamat_plg', 'LIKE', '%' . $request->search . '%')
                ->orWhere('harga_paket', 'LIKE', '%' . $request->search . '%');
        }

        $pelanggan = $query->get();


        return view('pelanggan.index', compact('pelanggan'));
    }



    public function create()
    {
        return view('pelanggan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)

    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'odp' => 'required',
            'maps' => 'required',
        ]);

        $pelanggan = new Pelanggan();
        $pelanggan->id_plg = $request->id_plg;
        $pelanggan->nama_plg = $request->nama_plg;
        $pelanggan->alamat_plg = $request->alamat_plg;
        $pelanggan->no_telepon_plg = $request->no_telepon_plg;
        $pelanggan->aktivasi_plg = $request->aktivasi_plg;
        $pelanggan->paket_plg = $request->paket_plg;
        $pelanggan->harga_paket = $request->harga_paket;
        $pelanggan->tgl_tagih_plg = $request->tgl_tagih_plg;
        $pelanggan->status_plg = $request->status_plg;
        $pelanggan->keterangan_plg = $request->keterangan_plg;
        $pelanggan->odp = $request->odp;
        $pelanggan->maps = $request->maps;

        $pelanggan->save();

        return redirect()->route('pelanggan.index');
    }

    /**
     * Display the specified resource.
     */


    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id_plg)
    {
        $pelanggan = Pelanggan::findOrFail($id_plg);
        return view('pelanggan.edit', compact('pelanggan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id_plg)
    {
        $pelanggan = Pelanggan::findOrFail($id_plg);
        $pelanggan->id_plg = $request->id_plg;
        $pelanggan->nama_plg = $request->nama_plg;
        $pelanggan->alamat_plg = $request->alamat_plg;
        $pelanggan->no_telepon_plg = $request->no_telepon_plg;
        $pelanggan->aktivasi_plg = $request->aktivasi_plg;
        $pelanggan->paket_plg = $request->paket_plg;
        $pelanggan->harga_paket = $request->harga_paket;
        $pelanggan->tgl_tagih_plg = $request->tgl_tagih_plg;
        $pelanggan->status_plg = $request->status_plg;
        $pelanggan->keterangan_plg = $request->keterangan_plg;
        $pelanggan->odp = $request->odp;
        $pelanggan->maps = $request->maps;


        $pelanggan->save();

        return redirect()->route('pelanggan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id_plg)
    {
        $pelanggan = Pelanggan::findOrFail($id_plg);
        $pelanggan->delete();

        return redirect()->route('pelanggan.index');
    }

    public function showOff($id)
    {
        // Ambil data pelanggan dari tabel pelanggan
        $pelanggan = Pelanggan::find($id);

        if ($pelanggan) {
            // Masukkan data ke tabel plg_of
            DB::table('plg_of')->insert([
                'id_plg' => $pelanggan->id_plg,
                'nama_plg' => $pelanggan->nama_plg,
                'alamat_plg' => $pelanggan->alamat_plg,
                'no_telepon_plg' => $pelanggan->no_telepon_plg,
                'aktivasi_plg' => $pelanggan->aktivasi_plg,
                'paket_plg' => $pelanggan->paket_plg,
                'harga_paket' => $pelanggan->harga_paket,
                'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
                'status_plg' => $pelanggan->status_plg,
                'odp' => $pelanggan->odp,
                'maps' => $pelanggan->maps,
                'keterangan_plg' => $pelanggan->keterangan_plg,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Hapus data dari tabel pelanggan
            $pelanggan->delete();

            // Redirect ke halaman pelanggan dengan pesan sukses
            return redirect()->route('pelangganof.index')->with('success', 'Pelanggan berhasil dipindahkan ke tabel pelanggan off.');
        } else {
            return redirect()->route('pelangganof.index')->with('error', 'Pelanggan tidak ditemukan.');
        }
    }
}
