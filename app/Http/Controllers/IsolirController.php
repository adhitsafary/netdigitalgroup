<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\IsolirModel;
use App\Models\Netnet;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelangganof;
use App\Models\PembayaranPelanggan;
use App\Models\Perbaikan;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IsolirController extends Controller
{
    public function index(Request $request)
    {
        // Mulai dengan query builder
        $query = IsolirModel::query();

        // Filter berdasarkan status pembayaran
        if ($request->filled('status_pembayaran')) {
            $status = $request->input('status_pembayaran');
            if ($status === 'belum_bayar') {
                $query->where('status_pembayaran', 'Belum Bayar');
            } elseif ($status === 'sudah_bayar') {
                $query->where('status_pembayaran', 'Sudah Bayar');
            }
        }

        // Pencarian
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

        // Filter berdasarkan tanggal tagih jika ada
        if ($request->filled('tgl_tagih_plg')) {
            $query->where('tgl_tagih_plg', $request->input('tgl_tagih_plg'));
        }

        // Filter berdasarkan paket pelanggan jika ada
        if ($request->filled('paket_plg')) {
            $query->where('paket_plg', $request->input('paket_plg'));
        }

        // Filter berdasarkan harga paket
        if ($request->filled('harga_paket')) {
            $query->where('harga_paket', $request->input('harga_paket'));
        }

        // Hitung total harga_paket dan total pelanggan sebelum pagination
        $totalJumlahPembayaran = $query->sum('harga_paket');
        $totalPelanggan = $query->count();

        // Eksekusi query dengan pagination
        $isolir = $query->paginate(200); // Ubah pagination jika diperlukan

        // Status pembayaran untuk keperluan view
        $status_pembayaran_display = $request->input('status_pembayaran', '');

        // Kembalikan view dengan data yang sudah difilter
        return view('isolir.index', compact('isolir', 'search', 'status_pembayaran_display', 'totalJumlahPembayaran', 'totalPelanggan'))
            ->with('success', 'Data isolir berhasil difilter.');
    }





    // Hapus pelanggan yang tidak aktif selama lebih dari 60 hari
    public function cleanUp()
    {
        $expired = IsolirModel::where('created_at', '<', now()->subDays(60))->get();

        foreach ($expired as $pelanggan) {
            $pelanggan->delete();
        }

        return redirect()->route('isolir.index')->with('success', 'Pelanggan yang tidak aktif selama lebih dari 60 hari berhasil dihapus.');
    }


    public function checkIsolirStatus()
    {
        // Ambil data pelanggan yang tgl_tagih_plg sudah lewat dan status pembayaran "belum bayar"
        $pelangganBelumBayar = Pelanggan::where('tgl_tagih_plg', '<', Carbon::now())
            ->where('status_pembayaran', 'belum bayar')
            ->get();

        foreach ($pelangganBelumBayar as $pelanggan) {
            // Pindahkan data ke tabel 'isolir'
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

            // Hapus data dari tabel 'pelanggan'
            $pelanggan->delete();
        }
    }

    public function pindahKeIsolir($id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Pindahkan data ke tabel 'isolir'
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

        // Hapus dari tabel pelanggan
        $pelanggan->delete();

        return redirect()->route('pelanggan.index')->with('success', 'Pelanggan berhasil dipindahkan ke Isolir.');
    }


    public function reactivatePelanggan(Request $request, $id)
    {
        $request->validate([
            'metode_transaksi' => 'required|string',
            'keterangan_plg' => 'nullable|string', // Keterangan bersifat opsional
        ]);

        $isolir = IsolirModel::findOrFail($id);

        // Pindahkan data ke tabel pelanggan
        $pelanggan = Pelanggan::create([
            'id_plg' => $isolir->id_plg,
            'nama_plg' => $isolir->nama_plg,
            'alamat_plg' => $isolir->alamat_plg,
            'no_telepon_plg' => $isolir->no_telepon_plg,
            'aktivasi_plg' => $isolir->aktivasi_plg,
            'paket_plg' => $isolir->paket_plg,
            'harga_paket' => $isolir->harga_paket,
            'tgl_tagih_plg' => $isolir->tgl_tagih_plg,
            'keterangan_plg' => $isolir->keterangan_plg,
            'odp' => $isolir->odp,
            'longitude' => $isolir->longitude,
            'latitude' => $isolir->latitude,
            'status_pembayaran' => $isolir->status_pembayaran,
        ]);

        // Hapus dari tabel isolir
        $isolir->delete();

        // Lakukan pembayaran otomatis
        $this->bayar(new Request([
            'id' => $pelanggan->id,
            'metode_transaksi' => $request->metode_transaksi, // Ambil metode transaksi dari request
            'keterangan_plg' => $request->keterangan_plg, // Ambil keterangan dari request
        ]));

        return redirect()->route('isolir.index')->with('success', 'Pelanggan Isolir berhasil diaktifkan kembali dan pembayaran telah dilakukan.');
    }



    public function checkPlgOffStatus()
    {
        $pelangganIsolir = IsolirModel::where('created_at', '<', Carbon::now()->subDays(60))->get();

        foreach ($pelangganIsolir as $pelanggan) {
            // Pindahkan data ke tabel 'plg_off'
            Pelangganof::create([
                'id_plg' => $pelanggan->id_plg,
                'nama_plg' => $pelanggan->nama_plg,

                // Tambahkan semua kolom yang diperlukan
            ]);

            // Hapus dari tabel isolir
            $pelanggan->delete();
        }
    }


    public function bayar(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|exists:pelanggan,id',
            'metode_transaksi' => 'required|string',
        ]);

        // Ambil data pelanggan berdasarkan id
        $pelanggan = Pelanggan::findOrFail($request->id);

        // Ambil data admin yang login atau default ke 'Unknown Admin' jika tidak ada
        $adminName = Auth::user() ? Auth::user()->name : 'Unknown Admin';

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

            // Tambahkan nama admin yang melakukan pembayaran
            'admin_name' => $adminName,
        ]);

        // Update status pembayaran pelanggan menjadi 'sudah bayar'
        $pelanggan->status_pembayaran = 'sudah bayar';
        $pelanggan->save();

        // Redirect ke halaman history pembayaran dengan pesan sukses
        return redirect()->route('pelanggan.historypembayaran', $pelanggan->id)
            ->with('success', 'Pembayaran berhasil dilakukan.');
    }

    public function filterByTanggalTagihindex(Request $request)
    {
        // Get filter inputs from the request
        $status_pembayaran_display = $request->input('status_pembayaran', '');
        $tanggal = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');

        // Start the query
        $query = IsolirModel::query();

        // Apply filters
        if ($status_pembayaran_display) {
            $query->where('status_pembayaran', $status_pembayaran_display);
        }

        if ($tanggal) {
            $query->where('tgl_tagih_plg', $tanggal);
        }

        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        if ($harga_paket) {
            $query->where('harga_paket', $harga_paket);
        }

        // Get paginated results
        $isolir = $query->paginate(100);

        // Calculate total payments and total users
        $totalJumlahPembayaran = $query->sum('harga_paket');
        $totalPelanggan = $query->count();

        // Return the data to the view
        return view('isolir.index', compact('isolir', 'harga_paket', 'paket_plg', 'tanggal', 'status_pembayaran_display', 'totalJumlahPembayaran', 'totalPelanggan'));
    }

    public function historypembayaran($id_plg)
    {
        // Mencari pelanggan di tabel Pelanggan
        $isolir = IsolirModel::find($id_plg);

        // Jika tidak ditemukan, cari di tabel IsolirModel
        if (!$isolir) {
            $isolir = IsolirModel::where('id_plg', $id_plg)->first();
        }

        // Jika pelanggan tidak ditemukan di kedua tabel
        if (!$isolir) {
            return redirect()->route('isolir.index')->with('error', 'Pelanggan tidak ditemukan.');
        }

        // Ambil riwayat pembayaran berdasarkan id_plg yang konsisten
        $pembayaran = BayarPelanggan::where('id_plg', $isolir->id_plg)->get();

        return view('isolir.historypembayaran', compact('isolir', 'pembayaran'));
    }

    public function toOff($id)
    {
        $isolir = IsolirModel::findOrFail($id);

        // Pindahkan data ke tabel isolir dengan pengecekan untuk kolom nullable
        Pelangganof::create([
            'id_plg' => $isolir->id_plg,
            'nama_plg' => $isolir->nama_plg,
            'alamat_plg' => $isolir->alamat_plg,
            'no_telepon_plg' => $isolir->no_telepon_plg ?? null,
            'aktivasi_plg' => $isolir->aktivasi_plg ?? null,
            'paket_plg' => $isolir->paket_plg ?? null,
            'harga_paket' => $isolir->harga_paket ?? null,
            'tgl_tagih_plg' => $isolir->tgl_tagih_plg ?? null, // Beri nilai null jika tidak diisi
            'keterangan_plg' => $isolir->keterangan_plg ?? '-', // Contoh nilai default jika kosong
            'odp' => $isolir->odp ?? null, // Beri nilai null jika tidak diisi
            'longitude' => $isolir->longitude ?? null, // Beri nilai null jika tidak diisi
            'latitude' => $isolir->latitude ?? null, // Beri nilai null jika tidak diisi
            'status_pembayaran' => $isolir->status_pembayaran ?? 'Belum Bayar', // Contoh nilai default
        ]);

        // Hapus dari tabel isolir
        $isolir->delete();

        return redirect()->route('pelangganof.index')->with('success', 'Pelanggan Isolir berhasil dimasukan ke data Pelangggan Off.');
    }
}
