<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Perbaikan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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


    public function store22(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
            'teknisi' => 'nullable', // Optional field for user to select a teknisi
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra'
        ];

        // Jika user memilih teknisi
        if ($request->has('teknisi') && !empty($request->teknisi)) {
            $teknisiDipilih = $request->teknisi;

            // Cek apakah teknisi yang dipilih masih dalam status "Proses"
            $teknisiStatusProses = Perbaikan::where('teknisi', $teknisiDipilih)
                ->where('status', 'Proses')
                ->exists();

            if ($teknisiStatusProses) {
                return redirect()->back()->with('error', 'Teknisi yang dipilih sedang dalam status Proses. Silakan pilih teknisi lain.');
            }
        } else {
            // Jika user tidak memilih teknisi, pilih secara acak dari teknisi yang tidak "Proses"
            $teknisiAvailable = [];
            foreach ($daftarTeknisi as $teknisi) {
                $teknisiStatusProses = Perbaikan::where('teknisi', $teknisi)
                    ->where('status', 'Proses')
                    ->doesntExist(); // Cari teknisi yang tidak punya status Proses
                if ($teknisiStatusProses) {
                    $teknisiAvailable[] = $teknisi;
                }
            }

            // Jika tidak ada teknisi yang tersedia (semua sedang "Proses"), tampilkan pesan error
            if (empty($teknisiAvailable)) {
                return redirect()->back()->with('error', 'Semua teknisi sedang dalam status Proses. Silakan coba lagi nanti.');
            }

            // Pilih teknisi secara acak dari daftar teknisi yang tersedia
            $teknisiDipilih = $teknisiAvailable[array_rand($teknisiAvailable)];
        }

        // Buat entri baru untuk perbaikan
        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih
        $perbaikan->teknisi = $teknisiDipilih;
        $perbaikan->status = 'Proses'; // Set status awal sebagai Proses
        $perbaikan->save();

        return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil ditambahkan');
    }

    public function store23(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
            'teknisi' => 'nullable', // Optional field for user to select a teknisi
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra'
        ];

        // Jika user memilih teknisi
        if ($request->has('teknisi') && !empty($request->teknisi)) {
            $teknisiDipilih = $request->teknisi;

            // Cek apakah teknisi yang dipilih masih dalam status "Proses"
            $teknisiStatusProses = Perbaikan::where('teknisi', $teknisiDipilih)
                ->where('status', 'Proses')
                ->exists();

            if ($teknisiStatusProses) {
                return redirect()->back()->with('error', 'Teknisi yang dipilih sedang dalam status Proses. Silakan pilih teknisi lain.');
            }
        } else {
            // Jika user tidak memilih teknisi, pilih secara acak dari teknisi yang tidak "Proses"
            $teknisiAvailable = [];
            foreach ($daftarTeknisi as $teknisi) {
                $teknisiStatusProses = Perbaikan::where('teknisi', $teknisi)
                    ->where('status', 'Proses')
                    ->doesntExist(); // Cari teknisi yang tidak punya status Proses
                if ($teknisiStatusProses) {
                    $teknisiAvailable[] = $teknisi;
                }
            }

            // Jika tidak ada teknisi yang tersedia (semua sedang "Proses"), tampilkan pesan error
            if (empty($teknisiAvailable)) {
                return redirect()->back()->with('error', 'Semua teknisi sedang dalam status Proses. Silakan coba lagi nanti.');
            }

            // Pilih teknisi secara acak dari daftar teknisi yang tersedia
            $teknisiDipilih = $teknisiAvailable[array_rand($teknisiAvailable)];
        }

        // Buat entri baru untuk perbaikan
        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih
        $perbaikan->teknisi = $teknisiDipilih;
        $perbaikan->status = 'Proses'; // Set status awal sebagai Proses
        $perbaikan->save();

        // Kirim pemberitahuan ke Telegram
        $botToken = '7085351448:AAErPRbIkJJOwkDTIMFUlwNU3AN_UQ1cRkY';
        $chatId = '5985430823';
        $message = "📣 *Perbaikan Baru Diterima*\n"
            . "🆔 ID Pelanggan: {$perbaikan->id_plg}\n"
            . "👤 Nama: {$perbaikan->nama_plg}\n"
            . "📍 Alamat: {$perbaikan->alamat_plg}\n"
            . "📞 Telepon: {$perbaikan->no_telepon_plg}\n"
            . "📦 Paket: {$perbaikan->paket_plg}\n"
            . "🔧 Teknisi: {$perbaikan->teknisi}\n"
            . "📋 Keterangan: {$perbaikan->keterangan}\n"
            . "🚦 Status: {$perbaikan->status}";

        $url = "https://api.telegram.org/bot{$botToken}/sendMessage";
        $data = [
            'chat_id' => $chatId,
            'text' => $message,
            'parse_mode' => 'Markdown'
        ];

        $client = new \GuzzleHttp\Client();
        $client->post($url, ['form_params' => $data]);

        return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil ditambahkan');
    }

    public function store2(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
            'teknisi' => 'nullable', // Optional field for user to select a teknisi
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra'
        ];

        // Jika user memilih teknisi
        if ($request->has('teknisi') && !empty($request->teknisi)) {
            $teknisiDipilih = $request->teknisi;

            // Cek apakah teknisi yang dipilih masih dalam status "Proses"
            $teknisiStatusProses = Perbaikan::where('teknisi', $teknisiDipilih)
                ->where('status', 'Proses')
                ->exists();

            if ($teknisiStatusProses) {
                return redirect()->back()->with('error', 'Teknisi yang dipilih sedang dalam status Proses. Silakan pilih teknisi lain.');
            }
        } else {
            // Jika user tidak memilih teknisi, pilih secara acak dari teknisi yang tidak "Proses"
            $teknisiAvailable = [];
            foreach ($daftarTeknisi as $teknisi) {
                $teknisiStatusProses = Perbaikan::where('teknisi', $teknisi)
                    ->where('status', 'Proses')
                    ->doesntExist(); // Cari teknisi yang tidak punya status Proses
                if ($teknisiStatusProses) {
                    $teknisiAvailable[] = $teknisi;
                }
            }

            // Jika tidak ada teknisi yang tersedia (semua sedang "Proses"), tampilkan pesan error
            if (empty($teknisiAvailable)) {
                return redirect()->back()->with('error', 'Semua teknisi sedang dalam status Proses. Silakan coba lagi nanti.');
            }

            // Pilih teknisi secara acak dari daftar teknisi yang tersedia
            $teknisiDipilih = $teknisiAvailable[array_rand($teknisiAvailable)];
        }

        // Buat entri baru untuk perbaikan
        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih
        $perbaikan->teknisi = $teknisiDipilih;
        $perbaikan->status = 'Proses'; // Set status awal sebagai Proses
        $perbaikan->save();

        // Kirim notifikasi ke Telegram
        $this->sendTelegramNotification($perbaikan);

        return redirect()->route('perbaikan.index')->with('success', 'Data perbaikan berhasil ditambahkan');
    }

    private function sendTelegramNotification2($perbaikan)
    {
        $token = '7085351448:AAErPRbIkJJOwkDTIMFUlwNU3AN_UQ1cRkY';
        $chat_id = '5985430823';
        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        $message =
            "📣 *Notifikasi Perbaikan Baru*\n" .
            "========================\n" .
            "🆔 *ID Pelanggan :* {$perbaikan->id_plg}\n" .
            "👤 *Nama :* {$perbaikan->nama_plg}\n" .
            "📍 *Alamat :* {$perbaikan->alamat_plg}\n" .
            "📞 *Telepon :* {$perbaikan->no_telepon_plg}\n" .
            "📦 *Paket :* {$perbaikan->paket_plg}\n" .
            "🔧 *Teknisi :* {$perbaikan->teknisi}\n" .
            "📋 *Keterangan :* {$perbaikan->keterangan}\n" .
            "🚦 *Status :* {$perbaikan->status}\n";

        $client = new \GuzzleHttp\Client();

        try {
            $client->post($url, [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
                ],
            ]);
        } catch (\Exception $e) {
            Log::error("Telegram Notification Error: " . $e->getMessage());
        }
    }





    public function store1(Request $request)
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

        // Cek teknisi yang saat ini tidak memiliki status "Proses"
        $teknisiAvailable = [];
        foreach ($daftarTeknisi as $key => $teknisi) {
            $teknisiStatusProses = Perbaikan::where('teknisi', $teknisi)
                ->where('status', 'Proses')
                ->doesntExist(); // Cari teknisi yang tidak punya status Proses
            if ($teknisiStatusProses) {
                $teknisiAvailable[$key] = $teknisi;
            }
        }

        // Jika tidak ada teknisi yang tersedia (semua sedang "Proses"), tampilkan pesan error
        if (empty($teknisiAvailable)) {
            return redirect()->back()->with('error', 'Semua teknisi sedang dalam status Proses. Silakan coba lagi nanti.');
        }

        // Pilih teknisi secara acak dari daftar teknisi yang tersedia
        $teknisiAcak = array_rand($teknisiAvailable);
        $teknisiDipilih = $teknisiAvailable[$teknisiAcak];

        // Buat entri baru untuk perbaikan
        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih setelah pengecekan
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
            'keterangan' => 'required',
            'teknisi' => 'nullable', // Teknisi tidak wajib diisi (opsional)
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra'
        ];

        // Cek apakah user memilih teknisi, jika tidak pilih secara acak
        if ($request->teknisi) {
            $teknisiDipilih = $request->teknisi;
        } else {
            // Pilih teknisi secara acak dari daftar
            $teknisiDipilih = $daftarTeknisi[array_rand($daftarTeknisi)];
        }

        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih
        $perbaikan->teknisi = $teknisiDipilih;


        $perbaikan->save();

        $this -> sendTelegramNotification($perbaikan);

        return redirect()->route('perbaikan.index')->with('success', 'Data PSB berhasil ditambahkan');
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
            'teknisi' => 'nullable', // Teknisi tidak wajib diisi (opsional)
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra'
        ];

        // Cek apakah user memilih teknisi, jika tidak pilih secara acak
        if ($request->teknisi) {
            $teknisiDipilih = $request->teknisi;
        } else {
            // Pilih teknisi secara acak dari daftar
            $teknisiDipilih = $daftarTeknisi[array_rand($daftarTeknisi)];
        }

        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;

        // Simpan teknisi yang dipilih
        $perbaikan->teknisi = $teknisiDipilih;


        $perbaikan->save();

        $this -> sendTelegramNotification($perbaikan);

        return redirect()->route('perbaikan.index')->with('success', 'Data PSB berhasil ditambahkan');
    }

    public function store_psb2(Request $request)
    {
        $request->validate([
            'id_plg' => 'required',
            'nama_plg' => 'required',
            'alamat_plg' => 'required',
            'no_telepon_plg' => 'required',
            'paket_plg' => 'required',
            'keterangan' => 'required',
            'teknisi' => 'nullable', // Teknisi tidak wajib diisi (opsional)
        ]);

        // Daftar teknisi berdasarkan tim
        $daftarTeknisi = [
            'Tim 1 Deden - Agis',
            'Tim 2 Mursidi - Dindin',
            'Tim 3 Isep - Indra'
        ];

        // Cek apakah user memilih teknisi, jika tidak pilih secara acak
        if ($request->teknisi) {
            $teknisiDipilih = $request->teknisi;
        } else {
            // Pilih teknisi secara acak dari daftar
            $teknisiDipilih = $daftarTeknisi[array_rand($daftarTeknisi)];
        }

        // Buat entri baru untuk PSB
        $perbaikan = new Perbaikan();
        $perbaikan->id_plg = $request->id_plg;
        $perbaikan->nama_plg = $request->nama_plg;
        $perbaikan->alamat_plg = $request->alamat_plg;
        $perbaikan->no_telepon_plg = $request->no_telepon_plg;
        $perbaikan->paket_plg = $request->paket_plg;
        $perbaikan->odp = $request->odp ?? null;
        $perbaikan->maps = $request->maps ?? null;
        $perbaikan->keterangan = $request->keterangan;
        $perbaikan->teknisi = $teknisiDipilih;
        $perbaikan->status = 'PSB'; // Status PSB
        $perbaikan->save();

        // Kirim notifikasi ke Telegram
        $this->sendTelegramNotification($perbaikan);

        return redirect()->route('perbaikan.index')->with('success', 'Data PSB berhasil ditambahkan');
    }

    private function sendTelegramNotification($perbaikan)
    {
        $adminName = auth()->user()->name;

        $token = '7085351448:AAErPRbIkJJOwkDTIMFUlwNU3AN_UQ1cRkY';
        $chat_id = '-1002333302498';
        $url = "https://api.telegram.org/bot{$token}/sendMessage";

        $message =
            "========================\n" .
            "📣 *Notifikasi Perbaikan Baru*\n" .
            "========================\n" .
            "🆔 *ID Pelanggan :* {$perbaikan->id_plg}\n" .
            "👤 *Nama :* {$perbaikan->nama_plg}\n" .
            "📍 *Alamat :* {$perbaikan->alamat_plg}\n" .
            "🗺️ *Maps :* \n" .
            "📞 *Telepon :* {$perbaikan->no_telepon_plg}\n" .
            "📦 *Paket :* {$perbaikan->paket_plg}\n" .
            "🔧 *Teknisi :* {$perbaikan->teknisi}\n" .
            "🚦 *Keterangan :* {$perbaikan->keterangan}\n\n" .
            "🙎🏻‍♂️ *Admin :* {$adminName}\n";


        $client = new \GuzzleHttp\Client();

        try {
            $client->post($url, [
                'form_params' => [
                    'chat_id' => $chat_id,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
                ],
            ]);
        } catch (\Exception $e) {
            Log::error("Telegram Notification Error: " . $e->getMessage());
        }
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

    public function rekapTeknisi1(Request $request)
    {
        $query = Perbaikan::query();
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        // Filter tanggal
        $query->whereBetween('created_at', [$startDate, $endDate]);

        // Pencarian berdasarkan berbagai kolom
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('teknisi', 'like', "%{$search}%");
            });
        }

        // Ambil data rekap teknisi dan total perbaikan
        $rekap = $query->selectRaw('teknisi, COUNT(*) as total')
            ->groupBy('teknisi')
            ->get();

        $totalPerbaikan = $rekap->sum('total');

        // Ambil semua data hasil filter untuk ditampilkan di tabel detail
        $perbaikan = $query->get();

        return view('perbaikan.rekap_teknisi', compact('rekap', 'totalPerbaikan', 'startDate', 'endDate', 'perbaikan'));
    }

    public function rekapTeknisi(Request $request)
    {
        $perbaikan = Perbaikan::all();
        $query = Perbaikan::query();
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date'))->startOfDay() : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date'))->endOfDay() : Carbon::now()->endOfMonth();

        // Filter tanggal
        $perbaikan->whereBetween('created_at', [$startDate, $endDate]);

        // Pencarian berdasarkan berbagai kolom
        $search = $request->input('search');
        if ($search) {
            $perbaikan->where(function ($perbaikan) use ($search) {
                $perbaikan->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('teknisi', 'like', "%{$search}%");
            });
        }

        // Ambil data rekap teknisi dan total perbaikan
        $rekap = $query->selectRaw('teknisi, COUNT(*) as total')
            ->groupBy('teknisi')
            ->get();

        $totalPerbaikan = $rekap->sum('total');

        // Ambil semua data hasil filter untuk ditampilkan di tabel detail

        return view('perbaikan.rekap_teknisi', compact('rekap', 'totalPerbaikan', 'startDate', 'endDate', 'perbaikan'));
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

        return redirect()->route('perbaikan.index')->with('success', 'Perbaikan telah selesai');
    }
}
