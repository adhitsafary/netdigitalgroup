<?php

namespace App\Http\Controllers;

use App\Models\Netnet;
use App\Models\Perbaikan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Export\PerbaikanExport;
use App\Models\Pelanggan;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class PerbaikanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function login()
    {
        return view('auth.login');
    }



    public function index(Request $request)
    {
        $query = Perbaikan::query();

        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Pencarian berdasarkan ID pelanggan atau nama pelanggan
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('id_plg', 'like', '%' . $request->search . '%')
                    ->orWhere('nama_plg', 'like', '%' . $request->search . '%');
            });
        }

        // Sorting berdasarkan tanggal pembuatan
        $sort = $request->get('sort', 'asc');
        $query->orderBy('created_at', $sort);

        // Ambil data perbaikan yang statusnya Proses
        $perbaikan = $query->where('status', 'Proses')->get();

        // Data untuk chart mingguan
        $weeklyData = Perbaikan::selectRaw('WEEK(created_at) as week, COUNT(*) as total')
            ->groupBy('week')
            ->pluck('total', 'week');

        // Data untuk chart bulanan
        $monthlyData = Perbaikan::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->groupBy('month')
            ->pluck('total', 'month');

        // Data untuk chart tahunan
        $yearlyData = Perbaikan::selectRaw('YEAR(created_at) as year, COUNT(*) as total')
            ->groupBy('year')
            ->pluck('total', 'year');

        return view('perbaikan.index', compact('perbaikan', 'sort', 'weeklyData', 'monthlyData', 'yearlyData'));
    }


    public function exportPdf(Request $request)
    {
        $query = Perbaikan::query();

        // Tambahkan filter jika perlu

        $perbaikan = $query->get();

        $pdf = Pdf::loadView('perbaikan.pdf', compact('perbaikan'));

        return $pdf->download('perbaikan.pdf');
    }

    /* public function exportExcel(Request $request)
    {
        return Excel::download(new PerbaikanExport($request), 'perbaikan.xlsx');
    } */


    public function create()
    {
        return view('perbaikan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            1 => 'Tim 1 Deden - Agis',
            2 => 'Tim 2 Mursidi - Dindin',
            3 => 'Tim 3 Isep - Indra'
        ];

        // Pilih teknisi secara acak dari daftar tim
        $teknisiAcak = array_rand($daftarTeknisi);
        $teknisiDipilih = $daftarTeknisi[$teknisiAcak];

        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih secara acak
        $perbaikan->teknisi = $teknisiDipilih;
        $perbaikan->status = 'Proses'; // Set status awal sebagai Proses
        $perbaikan->save();

        return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil ditambahkan');
    }




    //PSB
    public function create_psb()
    {
        return view('perbaikan.create_psb');
    }



    public function store_psb(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            1 => 'Tim 1 Deden - Agis',
            2 => 'Tim 2 Mursidi - Dindin',
            3 => 'Tim 3 Isep - Indra'
        ];

        // Pilih teknisi secara acak dari daftar tim
        $teknisiAcak = array_rand($daftarTeknisi);
        $teknisiDipilih = $daftarTeknisi[$teknisiAcak];

        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih secara acak
        $perbaikan->teknisi = $teknisiDipilih;
        $perbaikan->status = 'Proses'; // Set status awal sebagai Proses
        $perbaikan->save();

        return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil ditambahkan');
    }



    public function store_psb1(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            1 => 'Tim 1 Deden - Agis',
            2 => 'Tim 2 Mursidi - Dindin',
            3 => 'Tim 3 Isep - Indra'
        ];

        // Pilih tim teknisi secara acak
        $teknisiTim = rand(1, 3); // Menghasilkan angka antara 1 hingga 3

        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg; // Menggunakan nama_plg yang diinput
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null; // Menangani jika tidak diisi
        $perbaikan->maps = $request->maps ?? null; // Menangani jika tidak diisi
        $perbaikan->keterangan = $request->keterangan;

        // Simpan nama teknisi yang dipilih secara acak
        $perbaikan->teknisi = $daftarTeknisi[$teknisiTim];

        $perbaikan->save();

        return redirect()->route('perbaikan.index')->with('success', 'Data PSB berhasil ditambahkan');
    }





    public function getPelanggan($input)
    {
        // Coba cari pelanggan berdasarkan ID atau Nama Pelanggan
        $pelanggan = Pelanggan::where('id_plg', $input)
            ->orWhere('nama_plg', 'LIKE', '%' . $input . '%')
            ->first();

        if ($pelanggan) {
            return response()->json([
                'id_plg' => $pelanggan->id_plg,
                'nama_plg' => $pelanggan->nama_plg,
                'alamat_plg' => $pelanggan->alamat_plg,
                'no_telepon_plg' => $pelanggan->no_telepon_plg,
                'paket_plg' => $pelanggan->paket_plg,
                'odp' => $pelanggan->odp,
                'maps' => $pelanggan->maps
            ]);
        } else {
            return response()->json(null);
        }
    }

    public function searchPelanggan(Request $request)
    {
        $search = $request->input('search');

        $pelanggan = Pelanggan::where('nama_plg', 'like', '%' . $search . '%')
            ->get(['id_plg', 'nama_plg']); // Pilih kolom yang diperlukan untuk select2

        $results = [];

        foreach ($pelanggan as $p) {
            $results[] = [
                'id' => $p->id_plg,  // Ini yang akan menjadi value dari option
                'text' => $p->nama_plg  // Ini yang akan tampil di dropdown
            ];
        }

        return response()->json($results);
    }





    public function show(string $id)
    {
        //
    }


    public function edit(string $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        return view('perbaikan.edit', compact('perbaikan'));
    }


    public function update(Request $request, string $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp;
        $perbaikan->maps = $request->maps;
        $perbaikan->teknisi = $request->teknisi; // Biarkan user memilih teknisi saat update
        $perbaikan->keterangan = $request->keterangan;
        $perbaikan->update();

        return redirect()->route('perbaikan.index');
    }

    public function destroy(string $id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        $perbaikan->delete();

        return redirect()->route('perbaikan.index');
    }



    public function teknisi(Request $request)
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

        return view('perbaikan.teknisi', compact('perbaikan', 'sort', 'weeklyData', 'monthlyData', 'yearlyData'));
    }


    function home2()
    {
        return view('home2');
    }

    public function rekapTeknisi_asli()
    {
        $today = Carbon::now();
        $startDate = $today->copy()->startOfMonth();
        $endDate = $today->copy()->endOfMonth();
        $perbaikan = Perbaikan::findOrFail();

        $rekap = Perbaikan::selectRaw('teknisi, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('teknisi')
            ->get();

        // Reset rekap pada tanggal 25
        if ($today->day == 25) {
            // Hapus atau reset data rekap jika perlu
            // Misalnya dengan menambahkan kode reset di sini
        }

        return view('perbaikan.rekap_teknisi', compact('rekap'));
    }



    // Menambahkan schedule untuk reset data setiap tanggal 25
    public function resetTeknisiData()
    {
        $today = Carbon::now();

        if ($today->day == 25) {
            // Reset data teknisi
            // Misalnya menghapus atau mereset data tertentu jika diperlukan
            // Bisa menggunakan query builder atau model untuk mereset data
        }
    }

    public function rekapTeknisi(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        $rekap = Perbaikan::selectRaw('teknisi, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('teknisi')
            ->get();

        $totalPerbaikan = $rekap->sum('total');

        return view('perbaikan.rekap_teknisi', compact('rekap', 'totalPerbaikan', 'startDate', 'endDate'));
    }

    public function printRekapTeknisi(Request $request)
    {
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        $rekap = Perbaikan::selectRaw('teknisi, COUNT(*) as total')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('teknisi')
            ->get();

        $totalPerbaikan = $rekap->sum('total');

        $pdf = Pdf::loadView('perbaikan.print_rekap_teknisi', compact('rekap', 'totalPerbaikan', 'startDate', 'endDate'));

        return $pdf->download('rekap_teknisi_' . $startDate->format('Ymd') . '_to_' . $endDate->format('Ymd') . '.pdf');
    }


    public function resetData()
    {
        $today = Carbon::now();
        $startDate = $today->copy()->startOfMonth();
        $endDate = $today->copy()->endOfMonth();

        // Hapus atau reset data perbaikan dari bulan ini
        DB::table('perbaikan')->whereBetween('created_at', [$startDate, $endDate])->delete();

        return redirect()->route('perbaikan.rekapTeknisi')->with('status', 'Data perbaikan bulanan telah direset.');
    }

    public function selesai($id)
    {
        $perbaikan = Perbaikan::findOrFail($id);
        $perbaikan->status = 'selesai'; // Ubah status menjadi 'selesai'
        $perbaikan->save();

        return redirect()->route('perbaikan.index')->with('success', 'Perbaikan telah ditandai selesai');
    }
}
