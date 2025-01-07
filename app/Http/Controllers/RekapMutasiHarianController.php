<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\Pelanggan;
use App\Models\RekapMutasiHarianModel;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RekapMutasiHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua pelanggan dari tabel bayar_pelanggan
        $pelanggan = BayarPelanggan::select('pelanggan_id', 'nama_plg', 'paket_plg', 'tgl_tagih_plg')
            ->groupBy('pelanggan_id', 'nama_plg', 'paket_plg', 'tgl_tagih_plg')
            ->get();

 
        // Inisialisasi array untuk menampung hasil rekap
        $rekapMutasiHarian = [];

        // Looping untuk setiap pelanggan
        foreach ($pelanggan as $pel) {
            // Tanggal tagih dari tabel bayar_pelanggan
            $tanggalTagih = $pel->tgl_tagih_plg;

            // Hitung tagihan awal berdasarkan paket pelanggan
            $tagihanAwal = $pel->paket_plg;  // Asumsi ini berisi total tagihan paket

            // Pemasukan bulan Agustus (bulan sebelumnya)
            $pemasukanAgustus = BayarPelanggan::where('pelanggan_id', $pel->pelanggan_id)
                ->whereMonth('tanggal_pembayaran', '08')  // Agustus
                ->sum('jumlah_pembayaran');

            // Pemasukan bulan September (bulan saat ini)
            $pemasukanSeptember = BayarPelanggan::where('pelanggan_id', $pel->pelanggan_id)
                ->whereMonth('tanggal_pembayaran', '09')  // September
                ->sum('jumlah_pembayaran');

            // Saldo Akhir (Tagihan awal dikurangi dengan pemasukan bulan September)
            $saldoAkhir = $tagihanAwal - $pemasukanSeptember;

            // Total koreksi sama dengan tagihan awal
            $totalKoreksi = $tagihanAwal;

            // Hitung pelanggan yang belum membayar (belum tertagih)
            $belumTertagih = BayarPelanggan::where('pelanggan_id', $pel->pelanggan_id)
                ->whereNull('tanggal_pembayaran')  // Belum bayar
                ->sum('jumlah_pembayaran');

            // Hitung pelanggan yang sudah membayar (pemasukan harian)
            $pemasukanHarian = BayarPelanggan::where('pelanggan_id', $pel->pelanggan_id)
                ->whereNotNull('tanggal_pembayaran')  // Sudah bayar
                ->whereDay('tanggal_pembayaran', now()->day)  // Pembayaran harian
                ->sum('jumlah_pembayaran');

            // Piutang (Tagihan awal dikurangi dengan pemasukan harian)
            $piutang = $tagihanAwal - $pemasukanHarian;

            // Piutang masuk (pelanggan yang bayar setelah jatuh tempo)
            $piutangMasuk = BayarPelanggan::where('pelanggan_id', $pel->pelanggan_id)
                ->where('tgl_tagih_plg', '<', 'tanggal_pembayaran')  // Bayar terlambat
                ->sum('jumlah_pembayaran');

            // Pendapatan total (persentase pemasukan harian dari tagihan awal)
            $pendapatanTotal = ($pemasukanHarian / $tagihanAwal) * 100;

            // Masukkan data ke array rekap
            $rekapMutasiHarian[] = [
                'tanggal'           => $tanggalTagih,
                'tagihan_awal'      => $tagihanAwal,
                'pemasukan_agustus' => $pemasukanAgustus,
                'pemasukan_september' => $pemasukanSeptember,
                'saldo_akhir'       => $saldoAkhir,
                'total_koreksi'     => $totalKoreksi,
                'belum_tertagih'    => $belumTertagih,
                'pemasukan_harian'  => $pemasukanHarian,
                'piutang'           => $piutang,
                'piutang_masuk'     => $piutangMasuk,
                'pendapatan_total'  => $pendapatanTotal,
            ];
        }

        // Return data ke view
        return view('rekap_mutasi_harian.index', compact('rekapMutasiHarian'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
