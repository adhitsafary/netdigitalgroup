<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Carbon\Carbon;


class PembayaranMudahController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $query_cari = $request->input('q'); // Input dari pencarian

        // Jika tidak ada input pencarian, kembalikan koleksi kosong
        $pelanggan = collect();

        // Jika ada input pencarian, lakukan query ke database
        if ($query_cari) {
            $pelanggan = Pelanggan::with('pembayaran')
                ->where('id_plg', $query_cari)
                ->orWhere('nama_plg', 'LIKE', "%$query_cari%")
                ->paginate(10);
        }

        // Ambil nilai filter status pembayaran dari request
        $status_pembayaran_display = $request->input('status_pembayaran', '');
        $tanggal = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran');
        $tanggal_pembayaran = $request->input('tanggal_pembayaran');
        $created_at = $request->input('created_at');
        $bulan = $request->input('bulan'); // Ambil bulan dari request
        $date_start = $request->input('date_start'); // Ambil tanggal mulai dari request
        $date_end = $request->input('date_end'); // Ambil tanggal akhir dari request
        $search = $request->input('search'); // Ambil input pencarian dari request
        $untuk_pembayaran = $request->input('untuk_pembayaran');

        // Mulai query
        $query = BayarPelanggan::query(); // Ganti Pelanggan dengan BayarPelanggan

        // Filter berdasarkan status pembayaran jika ada
        if ($status_pembayaran_display) {
            $query->where('status_pembayaran', $status_pembayaran_display);
        }

        // Filter berdasarkan tanggal tagih jika ada
        if ($tanggal) {
            $query->where('tgl_tagih_plg', $tanggal);
        }

        // Filter berdasarkan paket pelanggan jika ada
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan harga paket jika ada
        if ($jumlah_pembayaran) {
            $query->where('jumlah_pembayaran', $jumlah_pembayaran);
        }

        // Filter berdasarkan tanggal pembayaran (format Y-m-d) jika ada
        if ($created_at) {
            $query->whereDate('created_at', $created_at);
        }

        // Filter berdasarkan bulan jika ada
        if ($bulan) {
            $query->whereMonth('tanggal_pembayaran', $bulan);
        }

        // Filter berdasarkan tanggal mulai dan tanggal akhir jika ada
        if ($date_start && $date_end) {
            $query->whereBetween('created_at', [$date_start, $date_end]);
        }

        // Filter berdasarkan pencarian jika ada
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('metode_transaksi', 'like', "%{$search}%");
            });
        }

        if ($untuk_pembayaran) {
            $query->where('untuk_pembayaran', $untuk_pembayaran);
        }


        // Filter hanya untuk hari ini
        $query->whereDate('created_at', Carbon::today());

        // Urutkan berdasarkan created_at terbaru
        $query->orderBy('created_at', 'desc');

        // Ambil hasil query
        $pembayaran = $query->paginate(100); // Ambil data yang telah difilter

        // Hitung total jumlah pembayaran yang telah difilter
        $totalJumlahPembayaran = $query->sum('jumlah_pembayaran');
        $totalPelanggan = $query->count(); // Menghitung jumlah pelanggan

        $totaljumlahpembayaranUntuk_filter = $query->sum('jumlah_pembayaran');
        $totalPelangganUntuk_filter = $query->count();


        //INI DATA FILTER DIATAS TEA

        // Hitung total jumlah pelanggan yang telah difilter
        $totalPelanggan = $query->count(); // Menghitung jumlah pelanggan

        //bayar Harian
        $tanggalHariIni = Carbon::now()->format('Y-m-d');
        $total_jml_user = BayarPelanggan::whereDate('created_at', $tanggalHariIni)->count();
        $total_user_bayar = BayarPelanggan::whereDate('created_at', $tanggalHariIni)->sum('jumlah_pembayaran');

        //Total Tagihan Hari Ini 
        $todayDay = Carbon::today()->day;
        $pembayaranHariiniPelanggan = Pelanggan::where('tgl_tagih_plg', $todayDay)->get();
        $totalTagihanHariIni = $pembayaranHariiniPelanggan->sum('harga_paket');

        // Hitung total pendapatan harian dari pembayaran
        $totalPendapatanharian_semua = BayarPelanggan::whereDate('tanggal_pembayaran', Carbon::today())->sum('jumlah_pembayaran');
        $totalUserHarian_semua = BayarPelanggan::whereDate('tanggal_pembayaran', Carbon::today())->count();

        //Total Belum Tertagih
        $jumlahPelangganMembayarHariIni = $pembayaranHariiniPelanggan->count();
        //total jumlah yang tertagih harian
        $totalTagihanTertagih = $totalTagihanHariIni - $totalPendapatanharian_semua;
        //total user yang tertagih harian
        $totalUserTertagih = $jumlahPelangganMembayarHariIni - $totalUserHarian_semua;


        // Ambil tanggal hari ini
        $today = Carbon::today()->format('Y-m-d');

        // Ambil data pembayaran piutang yang dilakukan pada hari ini berdasarkan created_at
        $pembayaranPiutang = BayarPelanggan::where('untuk_pembayaran', 'piutang')
            ->whereDate('created_at', $today)  // Menyaring berdasarkan tanggal hari ini menggunakan created_at
            ->get(['jumlah_pembayaran']);

        // Hitung total pembayaran piutang hari ini
        $totalPembayaranPiutang = $pembayaranPiutang->sum('jumlah_pembayaran');

        // Hitung jumlah pelanggan yang membayar piutang
        $jumlahPelangganPiutang = $pembayaranPiutang->count();




        return view('pembayaran_mudah.index', compact(
            'pelanggan',
            'query_cari', // Kirimkan query_cari sebagai nilai pencarian
            'pembayaran',
            'totalJumlahPembayaran',
            'totalPelanggan',
            'jumlah_pembayaran',
            'paket_plg',
            'tanggal',
            'status_pembayaran_display',
            'tanggal_pembayaran',
            'bulan',
            'date_start',
            'date_end',
            'search',
            'created_at',
            'total_jml_user',
            'total_user_bayar',
            'totalTagihanHariIni',
            'totalPendapatanharian_semua',
            'totalUserHarian_semua',
            'totalTagihanTertagih',
            'jumlahPelangganMembayarHariIni',
            'totalUserTertagih',
            'totalPembayaranPiutang',
            'jumlahPelangganPiutang',
            'pembayaranPiutang',
            'untuk_pembayaran',
            'totaljumlahpembayaranUntuk_filter',
            'totalPelangganUntuk_filter',

        ));
    }







    public function coba()
    {
        // Ambil tanggal hari ini
        $today = Carbon::today()->format('Y-m-d');

        // Ambil data pembayaran piutang yang dilakukan pada hari ini
        $pembayaranPiutang = BayarPelanggan::where('untuk_pembayaran', 'piutang')
            ->whereDate('created_at', $today)  // Menyaring berdasarkan tanggal hari ini
            ->get(['jumlah_pembayaran']);

        // Hitung total pembayaran piutang hari ini
        $totalPembayaran = $pembayaranPiutang->sum('jumlah_pembayaran');

        // Hitung jumlah pelanggan yang membayar piutang
        $jumlahPelanggan = $pembayaranPiutang->count();

        // Passing data ke view
        return view('pembayaran_mudah.coba', compact('totalPembayaran', 'jumlahPelanggan', 'pembayaranPiutang'));
    }


    public function bayar_hp(Request $request)
    {
        $query_cari = $request->input('q'); // Input dari pencarian

        // Jika tidak ada input pencarian, kembalikan koleksi kosong
        $pelanggan = collect();

        // Jika ada input pencarian, lakukan query ke database
        if ($query_cari) {
            $pelanggan = Pelanggan::with('pembayaran')
                ->where('id_plg', $query_cari)
                ->orWhere('nama_plg', 'LIKE', "%$query_cari%")
                ->paginate(10);
        }

        // Ambil nilai filter status pembayaran dari request
        $status_pembayaran_display = $request->input('status_pembayaran', '');
        $tanggal = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran');
        $tanggal_pembayaran = $request->input('tanggal_pembayaran');
        $created_at = $request->input('created_at');
        $bulan = $request->input('bulan'); // Ambil bulan dari request
        $date_start = $request->input('date_start'); // Ambil tanggal mulai dari request
        $date_end = $request->input('date_end'); // Ambil tanggal akhir dari request
        $search = $request->input('search'); // Ambil input pencarian dari request
        $untuk_pembayaran = $request->input('untuk_pembayaran');

        // Mulai query
        $query = BayarPelanggan::query(); // Ganti Pelanggan dengan BayarPelanggan

        // Filter berdasarkan status pembayaran jika ada
        if ($status_pembayaran_display) {
            $query->where('status_pembayaran', $status_pembayaran_display);
        }

        // Filter berdasarkan tanggal tagih jika ada
        if ($tanggal) {
            $query->where('tgl_tagih_plg', $tanggal);
        }

        // Filter berdasarkan paket pelanggan jika ada
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan harga paket jika ada
        if ($jumlah_pembayaran) {
            $query->where('jumlah_pembayaran', $jumlah_pembayaran);
        }

        // Filter berdasarkan tanggal pembayaran (format Y-m-d) jika ada
        if ($created_at) {
            $query->whereDate('created_at', $created_at);
        }

        // Filter berdasarkan bulan jika ada
        if ($bulan) {
            $query->whereMonth('tanggal_pembayaran', $bulan);
        }

        // Filter berdasarkan tanggal mulai dan tanggal akhir jika ada
        if ($date_start && $date_end) {
            $query->whereBetween('created_at', [$date_start, $date_end]);
        }

        // Filter berdasarkan pencarian jika ada
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('metode_transaksi', 'like', "%{$search}%");
            });
        }

        if ($untuk_pembayaran) {
            $query->where('untuk_pembayaran', $untuk_pembayaran);
        }


        // Filter hanya untuk hari ini
        $query->whereDate('created_at', Carbon::today());

        // Urutkan berdasarkan created_at terbaru
        $query->orderBy('created_at', 'desc');

        // Ambil hasil query
        $pembayaran = $query->paginate(100); // Ambil data yang telah difilter

        // Hitung total jumlah pembayaran yang telah difilter
        $totalJumlahPembayaran = $query->sum('jumlah_pembayaran');
        $totalPelanggan = $query->count(); // Menghitung jumlah pelanggan

        $totaljumlahpembayaranUntuk_filter = $query->sum('jumlah_pembayaran');
        $totalPelangganUntuk_filter = $query->count();

        //INI DATA FILTER DIATAS TEA

        // Hitung total jumlah pelanggan yang telah difilter
        $totalPelanggan = $query->count(); // Menghitung jumlah pelanggan

        //bayar Harian
        $tanggalHariIni = Carbon::now()->format('Y-m-d');
        $total_jml_user = BayarPelanggan::whereDate('created_at', $tanggalHariIni)->count();
        $total_user_bayar = BayarPelanggan::whereDate('created_at', $tanggalHariIni)->sum('jumlah_pembayaran');

        //Total Tagihan Hari Ini 
        $todayDay = Carbon::today()->day;
        $pembayaranHariiniPelanggan = Pelanggan::where('tgl_tagih_plg', $todayDay)->get();
        $totalTagihanHariIni = $pembayaranHariiniPelanggan->sum('harga_paket');

        // Hitung total pendapatan harian dari pembayaran
        $totalPendapatanharian_semua = BayarPelanggan::whereDate('tanggal_pembayaran', Carbon::today())->sum('jumlah_pembayaran');
        $totalUserHarian_semua = BayarPelanggan::whereDate('tanggal_pembayaran', Carbon::today())->count();

        //Total Belum Tertagih
        $jumlahPelangganMembayarHariIni = $pembayaranHariiniPelanggan->count();
        //total jumlah yang tertagih harian
        $totalTagihanTertagih = $totalTagihanHariIni - $totalPendapatanharian_semua;
        //total user yang tertagih harian
        $totalUserTertagih = $jumlahPelangganMembayarHariIni - $totalUserHarian_semua;


        // Ambil tanggal hari ini
        $today = Carbon::today()->format('Y-m-d');

        // Ambil data pembayaran piutang yang dilakukan pada hari ini berdasarkan created_at
        $pembayaranPiutang = BayarPelanggan::where('untuk_pembayaran', 'piutang')
            ->whereDate('created_at', $today)  // Menyaring berdasarkan tanggal hari ini menggunakan created_at
            ->get(['jumlah_pembayaran']);

        // Hitung total pembayaran piutang hari ini
        $totalPembayaranPiutang = $pembayaranPiutang->sum('jumlah_pembayaran');

        // Hitung jumlah pelanggan yang membayar piutang
        $jumlahPelangganPiutang = $pembayaranPiutang->count();

        return view('pembayaran_mudah.bayar_hp', compact(
            'pelanggan',
            'query_cari', // Kirimkan query_cari sebagai nilai pencarian
            'pembayaran',
            'totalJumlahPembayaran',
            'totalPelanggan',
            'jumlah_pembayaran',
            'paket_plg',
            'tanggal',
            'status_pembayaran_display',
            'tanggal_pembayaran',
            'bulan',
            'date_start',
            'date_end',
            'search',
            'created_at',
            'total_jml_user',
            'total_user_bayar',
            'totalTagihanHariIni',
            'totalPendapatanharian_semua',
            'totalUserHarian_semua',
            'totalTagihanTertagih',
            'jumlahPelangganMembayarHariIni',
            'totalUserTertagih',
            'totalPembayaranPiutang',
            'jumlahPelangganPiutang',
            'pembayaranPiutang',
            'untuk_pembayaran',
            'totaljumlahpembayaranUntuk_filter',
            'totalPelangganUntuk_filter',

        ));
    }








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
