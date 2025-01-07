<?php

namespace App\Http\Controllers;

use App\Models\KaryawanModel;
use App\Models\KasbonModel;
use App\Models\Netnet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\RekapPemasanganModel;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RekapPemasanganController extends Controller
{

    public function home()
    {

        $rekap_pemasangan = RekapPemasanganModel::all();

        // Kirim data ke view
        return view('rekap_pemasangan.index', compact('rekap_pemasangan'));
    }


    public function detail($id)
    {
        $rekap_pemasangan = RekapPemasanganModel::findOrFail($id);
        return view('rekap_pemasangan.detail', compact('rekap_pemasangan'));
    }


    public function index(Request $request)
    {
        $query = RekapPemasanganModel::query();

        $paket_plg = $request->input('paket_plg');
        $paket_plg = $request->input('nama');
        $paket_plg = $request->input('marketing');
        $nominal = $request->input('nominal');
        $tgl_pengajuan = $request->input('tgl_pengajuan');
        $tgl_aktivasi = $request->input('tgl_aktivasi');
        $registrasi = $request->input('registrasi'); // Filter jumlah pembayaran

        // Filter tanggal created_at
        if ($request->filled('created_at_dari')) {
            $query->whereDate('created_at', '>=', $request->created_at_dari);
        }

        if ($request->filled('created_at_sampai')) {
            $query->whereDate('created_at', '<=', $request->created_at_sampai);
        }

        // Filter berdasarkan tanggal tagih
        if ($tgl_pengajuan) {
            $query->where('tgl_pengajuan', $tgl_pengajuan);
        }

        if ($registrasi) {
            $query->where('registrasi', $registrasi);
        }


        if ($tgl_aktivasi) {
            $query->whereDate('tgl_aktivasi', $tgl_aktivasi);
        }

        // Filter berdasarkan paket pelanggan
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan harga paket
        if ($nominal) {
            $query->where('nominal', $nominal);
        }

        // Pencarian berdasarkan berbagai kolom
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('id_plg', $search)
                    ->orWhere('marketing', 'like', "%{$search}%")
                    ->orWhere('nama', 'like', "%{$search}%")
                    ->orWhere('no_telpon', 'like', "%{$search}%")
                    ->orWhere('registrasi', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }

        $rekap_pemasangan = $query->get();


        return view('rekap_pemasangan.index', compact('rekap_pemasangan'));
    }

    public function create()
    {


        return view('rekap_pemasangan.create');
    }



    public function store(Request $request)
    {
        $rekap_pemasangan = new RekapPemasanganModel();

        // Input data dari form
        $rekap_pemasangan->nik = $request->nik;
        $rekap_pemasangan->nama = $request->nama;
        $rekap_pemasangan->alamat = $request->alamat;
        $rekap_pemasangan->no_telpon = $request->no_telpon;
        $rekap_pemasangan->paket_plg = $request->paket_plg;
        $rekap_pemasangan->harga_paket = $request->harga_paket;
        $rekap_pemasangan->jt = \Carbon\Carbon::parse($request->tgl_aktivasi)->format('d');
        $rekap_pemasangan->status = 'Open';
        $rekap_pemasangan->tgl_pengajuan = $request->tgl_pengajuan;
        $rekap_pemasangan->registrasi = $request->registrasi;
        $rekap_pemasangan->marketing = $request->marketing;
        $rekap_pemasangan->keterangan_plg = $request->keterangan_plg;
        $rekap_pemasangan->id_plg = $request->id_plg;
        $rekap_pemasangan->odp = $request->odp;
        $rekap_pemasangan->longitude = $request->longitude;
        $rekap_pemasangan->latitude = $request->latitude;

        // tgl_aktivasi opsional
        if ($request->filled('tgl_aktivasi')) {
            $rekap_pemasangan->tgl_aktivasi = $request->tgl_aktivasi;
        }

        $rekap_pemasangan->save();

        return redirect()->route('rekap_pemasangan.index')->with('success', 'Data rekap_pemasangan berhasil disimpan.');
    }


    public function store1(Request $request)
    {

        $rekap_pemasangan = new RekapPemasanganModel();

        $rekap_pemasangan->nik = $request->nik; // Nama dari form input
        $rekap_pemasangan->nama = $request->nama;
        $rekap_pemasangan->alamat = $request->alamat;
        $rekap_pemasangan->no_telpon = $request->no_telpon; // Nama dari form input
        $rekap_pemasangan->tgl_aktivasi = $request->tgl_aktivasi;
        $rekap_pemasangan->paket_plg = $request->paket_plg;
        $rekap_pemasangan->harga_paket = $request->harga_paket; // Nama dari form input harga paket
        $rekap_pemasangan->jt = \Carbon\Carbon::parse($request->tgl_aktivasi)->format('d'); //ini tgl tagih pelanggan

        $rekap_pemasangan->status = 'Request Pasang'; //ini status pembayaran
        $rekap_pemasangan->tgl_pengajuan = $request->tgl_pengajuan; // Nama dari form input
        $rekap_pemasangan->registrasi = $request->registrasi;
        $rekap_pemasangan->marketing = $request->marketing;

        $rekap_pemasangan->keterangan_plg = $request->keterangan_plg;

        $rekap_pemasangan->id_plg = $request->id_plg;
        $rekap_pemasangan->odp = $request->odp;
        $rekap_pemasangan->longitude = $request->longitude;
        $rekap_pemasangan->latitude = $request->latitude;

        $rekap_pemasangan->save();

        // Redirect ke halaman rekap_pemasangan index setelah penyimpanan berhasil
        return redirect()->route('rekap_pemasangan.index')->with('success', 'Data rekap_pemasangan berhasil disimpan.');
    }


    public function aktivasi($id)
    {
        // Ambil data rekap pemasangan berdasarkan ID
        $rekapPemasangan = RekapPemasanganModel::find($id);

        if (!$rekapPemasangan) {
            return redirect()->back()->with('error', 'Data pemasangan tidak ditemukan.');
        }

        // Cek apakah pelanggan sudah ada di tabel `pelanggan`
        $existingPelanggan = Pelanggan::where('id_plg', $rekapPemasangan->id_plg)->first();

        if ($existingPelanggan) {
            return redirect()->route('pelanggan.psb')->with('error', 'Pelanggan ini sudah diaktivasi.');
        }

        // Simpan data pelanggan baru
        $pelanggan = new Pelanggan();
        $pelanggan->id_plg = $rekapPemasangan->id_plg;
        $pelanggan->nama_plg = $rekapPemasangan->nama;
        $pelanggan->alamat_plg = $rekapPemasangan->alamat;
        $pelanggan->no_telepon_plg = $rekapPemasangan->no_telpon;
        $pelanggan->paket_plg = $rekapPemasangan->paket_plg;
        $pelanggan->harga_paket = $rekapPemasangan->harga_paket;
        $pelanggan->odp = $rekapPemasangan->odp;
        $pelanggan->longitude = $rekapPemasangan->longitude;
        $pelanggan->aktivasi_plg = $rekapPemasangan->tgl_aktivasi;
        $pelanggan->latitude = $rekapPemasangan->latitude;
        $pelanggan->tgl_tagih_plg = \Carbon\Carbon::now()->format('d'); // Tagih di hari ini
        // $pelanggan->tgl_tagih_plg = \Carbon\Carbon::parse($rekapPemasangan->tgl_aktivasi)->format('d'); //ini tgl tagih pelanggan
        $pelanggan->status_pembayaran = 'PSB'; // Status awal PSB

        $pelanggan->save();

        return redirect()->route('rekap_pemasangan.index')->with('success', 'Pelanggan berhasil diaktivasi.');
    }



    public function store2(Request $request)
    {

        $rekap_pemasangan = new RekapPemasanganModel();

        // Isi data rekap_pemasangan
        $rekap_pemasangan->nik = $request->nik; // Nama dari form input
        $rekap_pemasangan->nama = $request->nama;
        $rekap_pemasangan->alamat = $request->alamat;
        $rekap_pemasangan->no_telpon = $request->no_telpon; // Nama dari form input
        $rekap_pemasangan->tgl_aktivasi = $request->tgl_aktivasi;
        $rekap_pemasangan->paket_plg = $request->paket_plg;
        $rekap_pemasangan->harga_paket = $request->harga_paket; // Nama dari form input
        $rekap_pemasangan->jt = $request->jt;
        $rekap_pemasangan->status = $request->status;
        $rekap_pemasangan->tgl_pengajuan = $request->tgl_pengajuan; // Nama dari form input
        $rekap_pemasangan->registrasi = $request->registrasi;
        $rekap_pemasangan->marketing = $request->marketing;



        // Simpan data rekap_pemasangan ke database
        $rekap_pemasangan->save();

        // Redirect ke halaman rekap_pemasangan index setelah penyimpanan berhasil
        return redirect()->route('rekap_pemasangan.index')->with('success', 'Data rekap_pemasangan berhasil disimpan.');
    }



    public function show(string $id) {}


    public function edit(string $id_plg)
    {
        $rekap_pemasangan = RekapPemasanganModel::findOrFail($id_plg);
        return view('rekap_pemasangan.edit', compact('rekap_pemasangan'));
    }


    public function update(Request $request, string $id_plg)
    {
        $rekap_pemasangan = RekapPemasanganModel::findOrFail($id_plg);

        $rekap_pemasangan->id_plg = $request->id_plg;
        $rekap_pemasangan->nik = $request->nik; // Nama dari form input
        $rekap_pemasangan->nama = $request->nama;
        $rekap_pemasangan->alamat = $request->alamat;
        $rekap_pemasangan->no_telpon = $request->no_telpon; // Nama dari form input
        $rekap_pemasangan->tgl_aktivasi = $request->tgl_aktivasi;
        $rekap_pemasangan->paket_plg = $request->paket_plg;
        $rekap_pemasangan->harga_paket = $request->harga_paket; // Nama dari form input
        $rekap_pemasangan->jt = $request->jt;
        $rekap_pemasangan->status = $request->status;
        $rekap_pemasangan->tgl_pengajuan = $request->tgl_pengajuan; // Nama dari form input
        $rekap_pemasangan->registrasi = $request->registrasi;
        $rekap_pemasangan->marketing = $request->marketing;


        $rekap_pemasangan->save();

        return redirect()->route('rekap_pemasangan.index');
    }


    public function destroy(string $id_plg)
    {
        $rekap_pemasangan = RekapPemasanganModel::findOrFail($id_plg);
        $rekap_pemasangan->delete();

        return redirect()->route('rekap_pemasangan.index');
    }

    public function updateTglTagihPlg()
    {
        // Ambil semua pelanggan
        $pelanggans = Pelanggan::all();

        foreach ($pelanggans as $pelanggan) {

            if ($pelanggan->tgl_tagih_plg == ' jadi tanggal sekarang') {
            }
        }
    }
}
