<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\IsolirModel;
use App\Models\Netnet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\Pelangganof;
use App\Models\PemasukanModel;
use App\Models\PembayaranPelanggan;
use App\Models\PengeluaranModel;
use App\Models\Perbaikan;
use App\Models\RekapPemasanganModel;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class PelangganController extends Controller
{

    public function home(Request $request)
    {
        // Ambil data pelanggan dan pelanggan off
        $pelanggan = Pelanggan::all();
        $pelangganof = Pelangganof::all();

        // Hitung total pendapatan bulanan
        $totalPendapatanBulanan = $pelanggan->sum('harga_paket');

        // Hitung total jumlah pengguna
        $totalJumlahPengguna = $pelanggan->count();

        // Hitung total pengurangan pendapatan dari pelanggan off bulanan
        $pelangganofuang = $pelangganof->sum('harga_paket');

        // Hitung total jumlah pengguna pelanggan off
        $pelangganoforang = $pelangganof->count();

        $pelanggan_of = $pelangganof->count();
        $pelanggan_of_uang = $pelangganof->sum('harga_paket');

        $totalpendapatanakhir = $totalPendapatanBulanan + $pelangganofuang;
        $totaluser = $totalJumlahPengguna + $pelangganoforang;

        // Penjualan harga paket
        $paketData = DB::table('pelanggan')
            ->select('harga_paket', DB::raw('count(*) as total_user'))
            ->groupBy('harga_paket')
            ->orderBy('total_user', 'desc')
            ->get();

        // Menghitung total user di semua paket
        $totalUsers = $paketData->sum('total_user');

        // Membagi data menjadi dua: 5 teratas dan sisanya
        $paketTop5 = $paketData->take(3); // Mengambil 5 teratas
        $paketRemaining = $paketData->skip(3);

        // Perbaikan dashboard
        $perbaikans = Perbaikan::all();

        // Pembayaran harian


        // Pendapatan bulanan
        $pendapatanBulanan = BayarPelanggan::selectRaw('MONTH(tanggal_pembayaran) as bulan, SUM(jumlah_pembayaran) as total_pendapatan')
            ->whereYear('tanggal_pembayaran', Carbon::now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Format data untuk dikirim ke view
        $dataPendapatan = array_fill(0, 12, 0); // Isi awal dengan 0 untuk 12 bulan
        foreach ($pendapatanBulanan as $pendapatan) {
            if ($pendapatan->bulan >= 9 && $pendapatan->bulan <= 12) {
                $dataPendapatan[$pendapatan->bulan - 1] = $pendapatan->total_pendapatan; // Sesuaikan indeks bulan
            }
        }

        // Ambil data chart untuk laporan
        $pembayaranPerBulan = BayarPelanggan::selectRaw('MONTH(tanggal_pembayaran) as bulan, SUM(jumlah_pembayaran) as total')
            ->whereYear('tanggal_pembayaran', Carbon::now()->year)
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get();

        // Siapkan array untuk data chart
        $dataChart = [
            'labels' => [],
            'data' => []
        ];

        // Pastikan data dimulai dari September
        $startMonth = 9; // September
        $endMonth = 12; // Desember

        foreach ($pembayaranPerBulan as $pembayaran) {
            $bulan = $pembayaran->bulan;
            if ($bulan >= $startMonth && $bulan <= $endMonth) {
                $dataChart['labels'][] = $this->getBulan($bulan);
                $dataChart['data'][] = $pembayaran->total;
            }
        }

        // Isi bulan-bulan sebelum September dengan 0
        for ($i = 1; $i < $startMonth; $i++) {
            $dataChart['labels'][] = $this->getBulan($i);
            $dataChart['data'][] = 0;
        }

        // Ambil total jumlah_pembayaran dari tabel berdasarkan bulan September (atau bulan lain dari tanggal saat ini)
        $bulanSekarang = now()->format('m'); // Mendapatkan bulan dari sistem (sekarang)
        $dataPendapatanbulan = DB::table('bayar_pelanggan')
            ->whereMonth('tanggal_pembayaran', $bulanSekarang)
            ->sum('jumlah_pembayaran'); // Menjumlahkan total pembayaran di bulan yang sama


        $tanggalMulai = $request->input('tanggal_mulai', now()->format('Y-m-d')); // Default ke hari ini
        $tanggalAkhir = $request->input('tanggal_akhir', now()->format('Y-m-d')); // Default ke hari ini
        // Ambil data pembayaran yang dilakukan antara tanggal mulai dan akhir (default hari ini)
        $pembayaranHarian = BayarPelanggan::whereBetween('tanggal_pembayaran', [$tanggalMulai, $tanggalAkhir])->get();
        // Hitung total pendapatan harian
        $totalPendapatanharian = $pembayaranHarian->sum('jumlah_pembayaran');
        // Hitung total pendapatan harian
        $totaluserhasilfilter = $pembayaranHarian->count();

        //INI BARU TOTAL HARIAN
        $tanggalHariIni = Carbon::now()->format('Y-m-d');
        // Mengambil total pemasukan dan pengeluaran untuk hari ini
        $totalPemasukan = PemasukanModel::whereDate('created_at', $tanggalHariIni)->sum('jumlah');
        $totalPengeluaran = PengeluaranModel::whereDate('created_at', $tanggalHariIni)->sum('jumlah');
        $total_user_bayar = BayarPelanggan::whereDate('created_at', $tanggalHariIni)->sum('jumlah_pembayaran');
        $totalRegistrasi = RekapPemasanganModel::whereDate('created_at', $tanggalHariIni)->sum('registrasi');

        $pembayaranHarian = BayarPelanggan::whereDate('tanggal_pembayaran', Carbon::today())
            ->where('metode_transaksi', '!=', 'TF') // Kecualikan metode transaksi 'TF'
            ->get();

        $pembayaranHarian_created_at = BayarPelanggan::whereDate('created_at', Carbon::today())
            ->where('metode_transaksi', '!=', 'TF') // Kecualikan metode transaksi 'TF'
            ->get();


        // $totalUserHarian = $pembayaranHarian->count(); ini harian tanggal
        $totalUserHarian = $pembayaranHarian_created_at->count();
        $totalPendapatanHarian = $pembayaranHarian_created_at->sum('jumlah_pembayaran');
        $pemasukantotal = $totalPemasukan - $totalPengeluaran;
        $totaljumlahsaldo = $totalPendapatanHarian + $pemasukantotal + $totalRegistrasi;
        //$totaljumlahsaldo = $totalRegistrasi + $totalsaldo;

        // Menghitung total jumlah pengguna yang membayar hari ini dari semua metode transaksi
        $totalUserHarian_semua = BayarPelanggan::whereDate('tanggal_pembayaran', Carbon::today())->count();

        // Hitung total pendapatan harian dari pembayaran
        $totalPendapatanharian_semua = BayarPelanggan::whereDate('tanggal_pembayaran', Carbon::today())
            ->sum('jumlah_pembayaran'); // Pastikan 'jumlah_pembayaran' adalah kolom yang menyimpan jumlah pembayaran


        //AMBIL TANGGAL TAGIH * JUMLAH PEMBAYARAN USER
        $todayDay = Carbon::today()->day;
        // Ambil semua pelanggan yang memiliki tgl_tagih_plg sama dengan hari ini (angka)
        $pembayaranHariiniPelanggan = Pelanggan::where('tgl_tagih_plg', $todayDay)->get();

        // Hitung total tagihan dari pelanggan yang harus membayar hari ini
        $totalTagihanHariIni = $pembayaranHariiniPelanggan->sum('harga_paket');

        // Hitung jumlah pelanggan yang membayar hari ini
        $jumlahPelangganMembayarHariIni = $pembayaranHariiniPelanggan->count();
        $total_jml_user = BayarPelanggan::whereDate('created_at', $tanggalHariIni)->count();


        //total jumlah yang tertagih harian
        $totalTagihanTertagih = $totalTagihanHariIni - $totalPendapatanharian_semua;
        //total user yang tertagih harian
        $totalUserTertagih = $jumlahPelangganMembayarHariIni - $totalUserHarian_semua;

        //INI CHART
        // Ambil data dari tabel bayar_pelanggan, kelompokkan berdasarkan tanggal, dan hitung jumlah pembayaran
        $pembayaranData = DB::table('bayar_pelanggan')
            ->select(DB::raw('DATE(tanggal_pembayaran) as tanggal'), DB::raw('COUNT(id) as total_user'), DB::raw('SUM(jumlah_pembayaran) as total_pembayaran'))
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Format data agar bisa digunakan di Chart.js
        $labels = [];
        $totalUsers = [];
        $totalPembayaran = [];

        foreach ($pembayaranData as $data) {
            $labels[] = $data->tanggal; // Menyimpan tanggal untuk label sumbu X
            $totalUsers[] = $data->total_user; // Jumlah user per tanggal
            $totalPembayaran[] = $data->total_pembayaran; // Total pembayaran per tanggal
        }



        // Kirim data ke view
        return view('index', compact(
            'pelanggan',
            'tanggalMulai',
            'tanggalAkhir',
            'dataPendapatanbulan',
            'totalJumlahPengguna', // Hanya dikirimkan sekali
            'dataPendapatan',
            'totalUserHarian',
            'totalPendapatanharian',
            'perbaikans',
            'totalUsers',
            'paketTop5',
            'paketRemaining',
            'paketData',
            'pelanggan_of',
            'pelanggan_of_uang',
            'totalpendapatanakhir',
            'totaluser',
            'pelangganofuang',
            'pelangganoforang',
            'totalPendapatanBulanan',
            'dataChart',
            //data baru
            'totalRegistrasi',
            //'totalsaldo',
            'totaljumlahsaldo',
            'totalPemasukan',
            'totalPengeluaran',
            'tanggalHariIni',
            'totalUserHarian_semua',
            'totalPendapatanharian_semua',
            'totaluserhasilfilter',

            //data pelanggan
            'pembayaranHariiniPelanggan',
            'jumlahPelangganMembayarHariIni',
            'totalTagihanHariIni',
            //Total Tertagih
            'totalTagihanTertagih',
            'totalUserTertagih',
            //chart baru
            'labels',
            'totalUsers',
            'totalPembayaran',
            //pembayaran hari ini total
            'total_user_bayar',
            'total_jml_user',




        ));
    }

    private function getBulan($bulan)
    {
        $bulanArray = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];
        return $bulanArray[$bulan];
    }

    public function detail($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        return view('pelanggan.detail', compact('pelanggan'));
    }



    public function index(Request $request)
    {
        // Ambil semua pelanggan
        $query = Pelanggan::query();

        // Pengecekan dan update status pembayaran otomatis berdasarkan tanggal tagihan
        $pelanggan_all = Pelanggan::all();

        // Ambil nilai filter dari request
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');

        // Proses pengecekan status pembayaran
        foreach ($pelanggan_all as $pelanggan) {
            $hari_tagih = intval($pelanggan->aktivasi_plg);
            $currentMonth = now()->month;
            $currentYear = now()->year;

            if ($hari_tagih >= 1 && $hari_tagih <= 31) {
                $tgl_tagih = Carbon::createFromDate($currentYear, $currentMonth, $hari_tagih);

                if (now()->gt($tgl_tagih)) {
                    $tgl_tagih = $tgl_tagih->addMonth();
                }

                if (now()->gt($tgl_tagih) && $pelanggan->status_pembayaran === 'sudah bayar') {
                    $pelanggan->status_pembayaran = 'Belum Bayar';
                    $pelanggan->save();
                }
            }
        }

        // Filter berdasarkan status pembayaran
        if ($request->filled('status_pembayaran')) {
            $status = $request->input('status_pembayaran');
            $query->where('status_pembayaran', $status === 'belum_bayar' ? 'Belum Bayar' : 'Sudah Bayar');
        }

        // Filter berdasarkan tanggal tagih
        if ($tgl_tagih_plg) {
            $query->where('tgl_tagih_plg', $tgl_tagih_plg);
        }

        // Filter berdasarkan paket pelanggan
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan harga paket
        if ($harga_paket) {
            $query->where('harga_paket', $harga_paket);
        }

        // Cek pencarian
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('aktivasi_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('tgl_tagih_plg', 'like', "%{$search}%");
            });
        }

        // Hitung total pelanggan keseluruhan (tidak terpengaruh filter)
        //$totalPelangganKeseluruhan = Pelanggan::count();

        $totalJumlahPembayaranKeseluruhan = Pelanggan::sum('harga_paket'); // Mengambil total keseluruhan dari tabel Pelanggan
        // Hitung total harga_paket dan total pelanggan berdasarkan filter
        $totalPelangganKeseluruhan = $query->count();
        // Hitung total pembayaran dari BayarPelanggan dalam bulan yang sama
        $totalJumlahPembayaran = BayarPelanggan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('jumlah_pembayaran');

        $userIdsWithPayments = BayarPelanggan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct('id_plg') // Pastikan hanya menghitung ID pelanggan yang unik
            ->pluck('id_plg'); // Ambil ID pelanggan dari pembayaran
        $totalPelangganBayar = count($userIdsWithPayments); // Hitung jumlah user

        $sisaPembayaran = $totalJumlahPembayaranKeseluruhan -  $totalJumlahPembayaran;
        $sisaUser = $totalPelangganKeseluruhan - $totalPelangganBayar;
        // Ambil data pelanggan
        $pelanggan = $query->with('pembayaranTerakhir')->paginate(100);
        $totalJumlahPembayaranfilter = $query->sum('pelanggan.harga_paket'); // Total pendapatan dari pelanggan
        $totalPelangganfilter = $query->count();

        // Return ke view dengan total pembayaran dan total pelanggan
        return view('pelanggan.index', compact(
            'totalPelangganfilter',
            'totalJumlahPembayaranfilter',
            'sisaPembayaran',
            'sisaUser',
            'totalJumlahPembayaranKeseluruhan',
            'totalPelangganKeseluruhan',
            'totalPelangganBayar',
            'totalJumlahPembayaran',
            'search',
            'pelanggan'
        ))->with('success', 'Status Pembayaran Pelanggan di-refresh ke tanggal 15 sebelum jatuh tempo.');
    }







    private function checkAndMoveToIsolirr($pelanggan)
    {
        // Isolir pelanggan jika sudah lewat batas tagihan
        if ($pelanggan->status_pembayaran === 'Belum Bayar') {
            IsolirModel::create([
                'id_plg' => $pelanggan->id_plg,
                'nama_plg' => $pelanggan->nama_plg,
                'alamat_plg' => $pelanggan->alamat_plg,
                'no_telepon_plg' => $pelanggan->no_telepon_plg,
                'aktivasi_plg' => $pelanggan->aktivasi_plg,
                'paket_plg' => $pelanggan->paket_plg,
                'harga_paket' => $pelanggan->harga_paket,
                'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
                'keterangan_plg' => $pelanggan->keterangan_plg,
                'odp' => $pelanggan->odp,
                'longitude' => $pelanggan->longitude,
                'latitude' => $pelanggan->latitude,
                'status_pembayaran' => $pelanggan->status_pembayaran,
            ]);

            // Hapus pelanggan dari tabel pelanggan
            $pelanggan->delete();
        }
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
            'tgl_tagih_plg' => 'tgl_tagih_plg'
        ]);

        $pelanggan = new Pelanggan();
        $pelanggan->id_plg = $request->id_plg;
        $pelanggan->nama_plg = $request->nama_plg;
        $pelanggan->alamat_plg = $request->alamat_plg;
        $pelanggan->no_telepon_plg = $request->no_telepon_plg;
        $pelanggan->aktivasi_plg = $request->aktivasi_plg;
        $pelanggan->paket_plg = $request->paket_plg;
        $pelanggan->harga_paket = $request->harga_paket;
        $pelanggan->keterangan_plg = $request->keterangan_plg;
        $pelanggan->odp = $request->odp;
        $pelanggan->longitude = $request->longitude;
        $pelanggan->latitude = $request->latitude;
        $pelanggan->tgl_tagih_plg = \Carbon\Carbon::parse($request->aktivasi_plg)->format('d');

        // Set status pembayaran awal sebagai 'belum bayar'
        $pelanggan->status_pembayaran = 'Belum Bayar';

        $pelanggan->save();

        return redirect()->route('pelanggan.index')->with('success', 'Pembayaran berhasil dilakukan.');
    }




    public function show(string $id) {}



    public function edit(string $id_plg)
    {
        $pelanggan = Pelanggan::findOrFail($id_plg);
        return view('pelanggan.edit', compact('pelanggan'));
    }

    public function update(Request $request, string $id_plg)
    {
        // Ambil data pelanggan yang sudah ada
        $pelanggan = Pelanggan::findOrFail($id_plg);

        // Update data pelanggan yang sudah ada
        $pelanggan->id_plg = $request->id_plg;
        $pelanggan->nama_plg = $request->nama_plg;
        $pelanggan->alamat_plg = $request->alamat_plg;
        $pelanggan->no_telepon_plg = $request->no_telepon_plg;
        $pelanggan->aktivasi_plg = $request->aktivasi_plg;
        $pelanggan->paket_plg = $request->paket_plg;
        $pelanggan->harga_paket = $request->harga_paket;
        $pelanggan->keterangan_plg = $request->keterangan_plg ?? null;
        $pelanggan->odp = $request->odp;
        $pelanggan->tgl_tagih_plg = $request->tgl_tagih_plg;
        $pelanggan->longitude = $request->longitude;
        $pelanggan->latitude = $request->latitude;

        // Simpan data yang sudah diperbarui
        $pelanggan->save();

        return redirect()->route('pelanggan.index');
    }





    public function destroy(string $id_plg)
    {
        $pelanggan = Pelanggan::findOrFail($id_plg);
        $pelanggan->delete();

        return redirect()->route('pelanggan.index');
    }

    public function pelanggan_off($id)
    {
        // Ambil data pelanggan dari tabel pelanggan
        $pelanggan = Pelanggan::find($id);

        DB::table('plg_off')->insert([
            'id_plg' => $pelanggan->id_plg,
            'nama_plg' => $pelanggan->nama_plg,
            'alamat_plg' => $pelanggan->alamat_plg,
            'no_telepon_plg' => $pelanggan->no_telepon_plg,
            'aktivasi_plg' => $pelanggan->aktivasi_plg,
            'paket_plg' => $pelanggan->paket_plg,
            'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
            'harga_paket' => $pelanggan->harga_paket,
            'odp' => $pelanggan->odp,
            'keterangan_plg' => $pelanggan->keterangan_plg,
            'longitude' => $pelanggan->longitude,
            'latitude' => $pelanggan->latitude,
            'status_pembayaran' => $pelanggan->status_pembayaran,
            'tgl_plg_off' => $pelanggan->created_at->format('Y-m-d'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Hapus data dari tabel pelanggan
        $pelanggan->delete();

        // Redirect ke halaman pelanggan dengan pesan sukses
        return redirect()->route('pelangganof.index')->with('success', 'Pelanggan berhasil dipindahkan ke tabel pelanggan off.');
    }



    public function toggleVisibility($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->is_visible = !$pelanggan->is_visible;
        $pelanggan->last_payment_date = now();
        $pelanggan->save();

        return redirect()->route('pelanggan.detail', $id)->with('status', 'Status visibilitas diubah.');
    }


    public function bayar(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|exists:pelanggan,id',
            // 'tanggal_pembayaran' => 'required|date',
            'metode_transaksi' => 'required|string',
        ]);

        // Ambil data pelanggan berdasarkan id
        $pelanggan = Pelanggan::findOrFail($request->id);
        //dd($pelanggan);

        // Simpan data ke tabel bayar_pelanggan
        BayarPelanggan::create([
            'pelanggan_id' => $pelanggan->id,
            'id_plg' => $pelanggan->id_plg ?? null,
            'nama_plg' => $pelanggan->nama_plg,
            'alamat_plg' => $pelanggan->alamat_plg,
            'aktivasi_plg' => $pelanggan->aktivasi_plg,
            'jumlah_pembayaran' => $pelanggan->harga_paket,
            'no_telepon_plg' => $pelanggan->no_telepon_plg,
            'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
            'paket_plg' => $pelanggan->paket_plg,
            'aktivasi_plg' => $pelanggan->aktivasi_plg,
            'metode_transaksi' => $request->metode_transaksi,
            'keterangan_plg' => $request->keterangan_plg,

            'tanggal_pembayaran' => Carbon::now()->format('Y-m') . '-' . $pelanggan->tgl_tagih_plg,


        ]);

        // Update status pembayaran pelanggan menjadi 'sudah bayar'
        $pelanggan->status_pembayaran = 'sudah bayar';
        $pelanggan->save();

        // Redirect ke halaman history pembayaran dengan pesan sukses
        return redirect()->route('pelanggan.historypembayaran', $pelanggan->id)
            ->with('success', 'Pembayaran berhasil dilakukan.');
    }

    public function historypembayaran($id_plg)
    {
        // Mencari pelanggan di tabel Pelanggan
        $pelanggan = Pelanggan::find($id_plg);

        // Jika tidak ditemukan, cari di tabel IsolirModel
        if (!$pelanggan) {
            $pelanggan = IsolirModel::where('id_plg', $id_plg)->first();
        }

        // Jika pelanggan tidak ditemukan di kedua tabel
        if (!$pelanggan) {
            return redirect()->route('pelanggan.index')->with('error', 'Pelanggan tidak ditemukan.');
        }

        // Ambil riwayat pembayaran berdasarkan id_plg yang konsisten
        $pembayaran = BayarPelanggan::where('id_plg', $pelanggan->id_plg)->get();

        return view('pelanggan.historypembayaran', compact('pelanggan', 'pembayaran'));
    }



    public function exportExcel(Request $request)
    {
        $search = $request->input('search');
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');

        $pembayaran = BayarPelanggan::when($search, function ($query, $search) {
            return $query->where('id', $search)
                ->orWhere('nama_plg', 'like', "%{$search}%");
        })
            ->when($date_start && $date_end, function ($query) use ($date_start, $date_end) {
                return $query->whereBetween('created_at', [$date_start, $date_end]);
            })
            ->get();

        return Excel::download(new PelangganController($pembayaran), 'pembayaran.xlsx');
    }


    public function plg_blm_byr(Request $request)
    {
        $search = $request->input('search');

        // Query untuk mendapatkan pelanggan yang belum membayar di bulan ini
        $pembayaran = DB::table('pelanggan')
            ->leftJoin('pembayaran', 'pelanggan.id_plg', '=', 'pembayaran.pelanggan_id')
            ->whereMonth('pembayaran.tanggal_pembayaran', '!=', now()->month)
            ->whereYear('pembayaran.tanggal_pembayaran', '=', now()->year)
            ->orWhereNull('pembayaran.tanggal_pembayaran')
            ->when($search, function ($query, $search) {
                return $query->where('pelanggan.id_plg', 'like', "%{$search}%")
                    ->orWhere('pelanggan.nama_plg', 'like', "%{$search}%");
            })
            ->select('pelanggan.id_plg', 'pelanggan.nama_plg', 'pelanggan.alamat_plg', 'pembayaran.tanggal_pembayaran', 'pembayaran.jumlah_pembayaran')
            ->get();

        return view('pembayaran.blm_byr', compact('pembayaran'));
    }


    public function getMonthlyPayments()
    {
        // Mengambil data jumlah pembayaran per bulan di tahun ini
        $monthlyPayments = DB::table('bayar_pelanggan')
            ->select(DB::raw('MONTH(tanggal_pembayaran) as month'), DB::raw('SUM(jumlah_pembayaran) as total_pendapatan'))
            ->whereYear('tanggal_pembayaran', Carbon::now()->year)
            ->groupBy(DB::raw('MONTH(tanggal_pembayaran)'))
            ->pluck('total_pendapatan', 'month')
            ->toArray();

        // Buat array dengan 12 bulan, isi 0 jika tidak ada data
        $dataPendapatan = array_fill(1, 12, 0);
        foreach ($monthlyPayments as $month => $total) {
            $dataPendapatan[$month] = $total;
        }

        // Kirim data ke view
        return view('chart', [
            'dataPendapatan' => $dataPendapatan,
        ]);
    }

    function teknisi(Request $request)
    {
        $query = Perbaikan::query();

        // Filtering
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Pencarian
        if ($request->filled('search')) {
            $query->where('id_plg', 'like', '%' . $request->search . '%')
                ->orWhere('nama_plg', 'like', '%' . $request->search . '%');
        }

        // Sorting
        $sort = $request->get('sort', 'asc');
        $query->orderBy('created_at', $sort);

        $perbaikan = $query->get();

        // Data for charts
        $weeklyData = $query->selectRaw('WEEK(created_at) as week, COUNT(*) as total')
            ->groupBy('week')
            ->pluck('total', 'week');

        $monthlyData = $query->selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        $yearlyData = $query->selectRaw('YEAR(created_at) as year, COUNT(*) as total')
            ->groupBy('year')
            ->pluck('total', 'year');

        return view('perbaikan.index', compact('perbaikan', 'sort', 'weeklyData', 'monthlyData', 'yearlyData'));
    }

    function admin()
    {
        return view('index');
    }

    function superadmin()
    {
        return view('index');
    }

    public function belumBayar()
    {
        $pelanggan = Pelanggan::where('status_pembayaran', 'belum bayar')->get();
        return view('pelanggan.belum_bayar', compact('pelanggan'));
    }

    //UPDATE STATUS INDEX
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_pembayaran' => 'required|in:Belum Bayar,Sudah Bayar',
        ]);

        $pelanggan = Pelanggan::findOrFail($id);

        // Gunakan strcasecmp untuk membandingkan tanpa memperhatikan huruf kapital
        if (strcasecmp($request->status_pembayaran, 'Belum Bayar') === 0) {
            $pelanggan->status_pembayaran = 'Belum Bayar';
        } elseif (strcasecmp($request->status_pembayaran, 'Sudah Bayar') === 0) {
            $pelanggan->status_pembayaran = 'Sudah Bayar';
        }

        $pelanggan->save();

        return redirect()->route('pelanggan.index')->with('success', 'Status pembayaran berhasil diperbarui.');
    }

    public function toIsolir($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Pindahkan data ke tabel pelanggan
        IsolirModel::create([
            'id_plg' => $pelanggan->id_plg,
            'nama_plg' => $pelanggan->nama_plg,
            'alamat_plg' => $pelanggan->alamat_plg,
            'no_telepon_plg' => $pelanggan->no_telepon_plg,
            'aktivasi_plg' => $pelanggan->aktivasi_plg,
            'paket_plg' => $pelanggan->paket_plg,
            'harga_paket' => $pelanggan->harga_paket,
            'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
            'keterangan_plg' => $pelanggan->keterangan_plg,
            'odp' => $pelanggan->odp,
            'longitude' => $pelanggan->longitude,
            'latitude' => $pelanggan->latitude,
            'status_pembayaran' => $pelanggan->status_pembayaran,
        ]);

        // Hapus dari tabel isolir
        $pelanggan->delete();

        return redirect()->route('isolir.index')->with('success', 'Pelanggan berhasil di masukan ke Isolir.');
    }

    public function filterByTanggalTagih(Request $request)
    {
        // Ambil nilai filter status pembayaran dari request
        $status_pembayaran_display = $request->input('status_pembayaran', '');

        // Ambil tanggal tagih dan paket dari request
        $tanggal = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');

        // Mulai query
        $query = Pelanggan::query();

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

        // Lakukan pagination pada query
        $pelanggan = $query->paginate(100);

        // Kembalikan data pelanggan ke view
        return view('pelanggan.index_tagih', compact('pelanggan', 'paket_plg', 'tanggal', 'status_pembayaran_display'));
    }

    public function filterByTanggalTagihindex(Request $request)
    {
        // Ambil nilai filter dari request
        $status_pembayaran_display = $request->input('status_pembayaran', '');
        $tanggal_tagih = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $filter = $request->input('filter');
        $tanggal_pembayaran = $request->input('created_at');

        // Query dasar pelanggan dengan pembayaran terakhir
        $query = Pelanggan::with('pembayaranTerakhir')
            ->leftJoin('bayar_pelanggan', 'pelanggan.id_plg', '=', 'bayar_pelanggan.id_plg')
            ->select('pelanggan.*', 'bayar_pelanggan.created_at as pembayaranTerakhir');

        // Filter berdasarkan status pembayaran jika ada
        if ($status_pembayaran_display) {
            $query->where('pelanggan.status_pembayaran', $status_pembayaran_display === 'belum_bayar' ? 'Belum Bayar' : 'Sudah Bayar');
        }

        // Filter berdasarkan tanggal tagih
        if ($tanggal_tagih) {
            $query->where('pelanggan.tgl_tagih_plg', $tanggal_tagih);
        }

        // Filter berdasarkan paket pelanggan
        if ($paket_plg) {
            $query->where('pelanggan.paket_plg', $paket_plg);
        }

        // Filter berdasarkan harga paket
        if ($harga_paket) {
            $query->where('pelanggan.harga_paket', $harga_paket);
        }

        // Filter berdasarkan tanggal pembayaran
        if ($tanggal_pembayaran) {
            $query->whereDate('bayar_pelanggan.created_at', '=', $tanggal_pembayaran);
        }

        // Urutkan berdasarkan pembayaran terbaru atau terlama
        if ($filter == 'terbaru') {
            $query->orderBy('bayar_pelanggan.created_at', 'desc');
        } elseif ($filter == 'terlama') {
            $query->orderBy('bayar_pelanggan.created_at', 'asc');
        }

        // Ambil data yang difilter
        $pelanggan = $query->paginate(100);

        // Hitung total harga paket berdasarkan filter
        $totalJumlahPembayaranfilter = $query->sum('pelanggan.harga_paket'); // Total pendapatan dari pelanggan yang difilter

        // Hitung total pelanggan berdasarkan filter
        $totalPelangganfilter = $query->count();

        // Hitung total jumlah pembayaran keseluruhan (tanpa filter)
        $totalJumlahPembayaranKeseluruhan = Pelanggan::sum('harga_paket'); // Total keseluruhan dari tabel Pelanggan
        $totalPelangganKeseluruhan = Pelanggan::count(); // Total pelanggan keseluruhan

        // Hitung total pembayaran dalam bulan ini
        $totalJumlahPembayaran = BayarPelanggan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('jumlah_pembayaran');

        // Hitung total pelanggan yang sudah membayar dalam bulan ini
        $totalPelangganBayar = BayarPelanggan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct('id_plg')
            ->count('id_plg');

        // Hitung sisa pembayaran dan sisa pelanggan yang belum membayar
        $sisaPembayaran = $totalJumlahPembayaranKeseluruhan - $totalJumlahPembayaran;
        $sisaUser = $totalPelangganKeseluruhan - $totalPelangganBayar;

        // Kembalikan data ke view dengan total jumlah pembayaran dan pelanggan
        return view('pelanggan.index', compact(
            'totalJumlahPembayaran',

            'totalJumlahPembayaranfilter',
            'sisaPembayaran',
            'sisaUser',
            'totalPelangganKeseluruhan',
            'totalPelangganfilter',
            'totalPelangganBayar',
            'totalJumlahPembayaranKeseluruhan',
            'pelanggan',
            'harga_paket',
            'paket_plg',
            'tanggal_tagih',
            'status_pembayaran_display',
            'tanggal_pembayaran'
        ));
    }


    public function checkAndMoveToIsolir()
    {
        // Ambil semua pelanggan
        $pelangganList = Pelanggan::all();

        // Tanggal hari ini
        $today = Carbon::now();

        foreach ($pelangganList as $pelanggan) {
            // Ambil tanggal pembayaran terakhir dari tabel BayarPelanggan
            $pembayaranTerakhir = BayarPelanggan::where('id_plg', $pelanggan->id_plg)
                ->orderBy('created_at', 'desc')
                ->first();

            // Cek apakah ada pembayaran terakhir
            if ($pembayaranTerakhir) {
                $createdAt = \Carbon\Carbon::parse($pembayaranTerakhir->created_at);
            } else {
                // Jika belum ada pembayaran sama sekali, anggap pembayaran terlambat lebih dari 1 bulan
                $createdAt = null;
            }

            // Pecah 'tgl_tagih_plg' menjadi array (misal: '20' atau '1,15,25')
            $tglTagihArray = explode(',', $pelanggan->tgl_tagih_plg);
            $maxTglTagih = max($tglTagihArray); // Tanggal maksimum tagihan dalam bulan tersebut

            // Validasi $maxTglTagih agar berada di rentang 1 sampai 31
            if ($maxTglTagih < 1 || $maxTglTagih > 31) {
                // Jika tidak valid, lewati iterasi ini
                continue;
            }

            // Buat tanggal tagih bulan ini dengan validasi
            try {
                $tglTagihBulanIni = Carbon::createFromDate($today->year, $today->month, $maxTglTagih);
            } catch (\Exception $e) {
                // Jika terjadi error saat membuat tanggal, skip pelanggan ini
                continue;
            }

            // Cek apakah tanggal pembayaran terakhir sudah lebih dari 1 bulan
            if (!$createdAt || $createdAt->lt($today->copy()->subMonth())) {
                // Cek apakah sudah melewati tanggal tagih bulan ini
                if ($today->greaterThan($tglTagihBulanIni)) {
                    // Pelanggan belum bayar dan sudah melewati batas tagih
                    IsolirModel::create([
                        'id_plg' => $pelanggan->id_plg,
                        'nama_plg' => $pelanggan->nama_plg,
                        'alamat_plg' => $pelanggan->alamat_plg,
                        'no_telepon_plg' => $pelanggan->no_telepon_plg,
                        'aktivasi_plg' => $pelanggan->aktivasi_plg,
                        'paket_plg' => $pelanggan->paket_plg,
                        'harga_paket' => $pelanggan->harga_paket,
                        'tgl_tagih_plg' => $pelanggan->tgl_tagih_plg,
                        'keterangan_plg' => $pelanggan->keterangan_plg,
                        'odp' => $pelanggan->odp,
                        'longitude' => $pelanggan->longitude,
                        'latitude' => $pelanggan->latitude,
                        'status_pembayaran' => $pelanggan->status_pembayaran,
                    ]);

                    // Hapus pelanggan dari tabel pelanggan setelah dipindahkan ke isolir
                    $pelanggan->delete();
                }
            }
        }

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan yang melewati batas sudah dipindahkan ke isolir.');
    }

    //dengan checkAndMoveToIsolir ketika sudah 1
    public function updatePaymentStatus()
    {
        // Ambil semua pelanggan
        $pelanggans = Pelanggan::all();

        foreach ($pelanggans as $pelanggan) {
            // Ambil pembayaran terakhir dari tabel BayarPelanggan berdasarkan id_plg
            $pembayaranTerakhir = BayarPelanggan::where('id_plg', $pelanggan->id_plg)
                ->orderBy('created_at', 'desc')
                ->first();

            // Jika ada pembayaran terakhir, ambil tanggalnya
            if ($pembayaranTerakhir) {
                $createdAt = \Carbon\Carbon::parse($pembayaranTerakhir->created_at);
            } else {
                // Jika belum ada pembayaran, anggap belum ada pembayaran (default ke null)
                $createdAt = null;
            }

            // Pisahkan tanggal tagihan menjadi array dan ambil tanggal terakhir
            $tglTagihArray = explode(',', $pelanggan->tgl_tagih_plg);
            $tglTagihTerakhir = end($tglTagihArray); // Ambil tanggal terakhir dari array

            // Cek apakah $tglTagihTerakhir adalah angka dan valid
            if (is_numeric($tglTagihTerakhir)) {
                $currentYear = Carbon::now()->year; // Ambil tahun saat ini
                $currentMonth = Carbon::now()->month; // Ambil bulan saat ini

                // Buat tanggal lengkap dengan format 'Y-m-d' (contohnya: '2024-09-25')
                $tglTagihPlg = Carbon::createFromFormat('Y-m-d', "$currentYear-$currentMonth-$tglTagihTerakhir");

                // Hitung H-5 dari tgl_tagih_plg
                $tanggalH5 = $tglTagihPlg->subDays(0);

                // Cek apakah pelanggan sudah membayar pada bulan ini atau bulan depan
                if ($createdAt && ($createdAt->month == Carbon::now()->month || $createdAt->month == Carbon::now()->addMonth()->month) && $createdAt->year == Carbon::now()->year) {
                    // Jika sudah bayar pada bulan ini atau bulan depan, tetap set status menjadi "Sudah Bayar"
                    $pelanggan->status_pembayaran = 'Sudah Bayar';
                    $pelanggan->save();
                } else {
                    // Jika belum bayar bulan ini dan sudah H-5 dari tanggal tagih
                    if (Carbon::now()->greaterThanOrEqualTo($tanggalH5)) {
                        // Ubah status menjadi "Belum Bayar"
                        $pelanggan->status_pembayaran = 'Belum Bayar';
                        $pelanggan->save();
                    }
                }
            }
        }

        return redirect()->route('pelanggan.index')->with('success', 'Status Pembayaran Pelanggan telah diperbarui.');
    }


    public function indexcoba(Request $request)
    {
        // Ambil semua pelanggan
        $query = Pelanggan::query();

        // Pengecekan dan update status pembayaran otomatis berdasarkan tanggal tagihan
        $pelanggan_all = Pelanggan::all();

        // Ambil nilai filter dari request
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');

        // Proses pengecekan status pembayaran
        foreach ($pelanggan_all as $pelanggan) {
            $hari_tagih = intval($pelanggan->aktivasi_plg);
            $currentMonth = now()->month;
            $currentYear = now()->year;

            if ($hari_tagih >= 1 && $hari_tagih <= 31) {
                $tgl_tagih = Carbon::createFromDate($currentYear, $currentMonth, $hari_tagih);

                if (now()->gt($tgl_tagih)) {
                    $tgl_tagih = $tgl_tagih->addMonth();
                }

                if (now()->gt($tgl_tagih) && $pelanggan->status_pembayaran === 'sudah bayar') {
                    $pelanggan->status_pembayaran = 'Belum Bayar';
                    $pelanggan->save();
                }
            }
        }

        // Filter berdasarkan status pembayaran
        if ($request->filled('status_pembayaran')) {
            $status = $request->input('status_pembayaran');
            $query->where('status_pembayaran', $status === 'belum_bayar' ? 'Belum Bayar' : 'Sudah Bayar');
        }

        // Filter berdasarkan tanggal tagih
        if ($tgl_tagih_plg) {
            $query->where('tgl_tagih_plg', $tgl_tagih_plg);
        }

        // Filter berdasarkan paket pelanggan
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan harga paket
        if ($harga_paket) {
            $query->where('harga_paket', $harga_paket);
        }

        // Cek pencarian
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('aktivasi_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('tgl_tagih_plg', 'like', "%{$search}%");
            });
        }

        // Hitung total pelanggan keseluruhan (tidak terpengaruh filter)
        //$totalPelangganKeseluruhan = Pelanggan::count();

        $totalJumlahPembayaranKeseluruhan = Pelanggan::sum('harga_paket'); // Mengambil total keseluruhan dari tabel Pelanggan
        // Hitung total harga_paket dan total pelanggan berdasarkan filter
        $totalPelangganKeseluruhan = $query->count();
        // Hitung total pembayaran dari BayarPelanggan dalam bulan yang sama
        $totalJumlahPembayaran = BayarPelanggan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('jumlah_pembayaran');

        $userIdsWithPayments = BayarPelanggan::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->distinct('id_plg') // Pastikan hanya menghitung ID pelanggan yang unik
            ->pluck('id_plg'); // Ambil ID pelanggan dari pembayaran
        $totalPelangganBayar = count($userIdsWithPayments); // Hitung jumlah user

        $sisaPembayaran = $totalJumlahPembayaranKeseluruhan -  $totalJumlahPembayaran;
        $sisaUser = $totalPelangganKeseluruhan - $totalPelangganBayar;
        // Ambil data pelanggan
        $pelanggan = $query->with('pembayaranTerakhir')->paginate(100);
        $totalJumlahPembayaranfilter = $query->sum('pelanggan.harga_paket'); // Total pendapatan dari pelanggan
        $totalPelangganfilter = $query->count();

        // Return ke view dengan total pembayaran dan total pelanggan
        return view('pelanggan.indexcoba', compact(
            'totalPelangganfilter',
            'totalJumlahPembayaranfilter',
            'sisaPembayaran',
            'sisaUser',
            'totalJumlahPembayaranKeseluruhan',
            'totalPelangganKeseluruhan',
            'totalPelangganBayar',
            'totalJumlahPembayaran',
            'search',
            'pelanggan'
        ))->with('success', 'Status Pembayaran Pelanggan di-refresh ke tanggal 15 sebelum jatuh tempo.');
    }

    public function pembayaranTerakhir()
    {
        return $this->hasOne(BayarPelanggan::class, 'id_plg')->latest('created_at');
    }
}
