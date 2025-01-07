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
use Illuminate\Support\Facades\Log;

class PelangganOfController extends Controller
{

    public function home()
    {

        $pelangganof = Pelangganof::all();

        // Hitung total pendapatan bulanan
        $totalPendapatanBulanan = $pelangganof->sum('harga_paket');

        // Hitung total jumlah pengguna
        $totalJumlahPengguna = $pelangganof->count();

        // Kirim data ke view
        return view('index', compact('pelangganof', 'totalPendapatanBulanan', 'totalJumlahPengguna'));
    }


    public function detail($id)
    {
        $pelangganof = Pelangganof::findOrFail($id);
        return view('pelangganof.detail', compact('pelangganof'));
    }


    public function index(Request $request)
    {
        $query = Pelangganof::query();

        if ($request->has('search')) {
            $query->where('nama_plg', 'LIKE', '%' . $request->search . '%')
                ->orWhere('id_plg', 'LIKE', '%' . $request->search . '%')
                ->orWhere('alamat_plg', 'LIKE', '%' . $request->search . '%')
                ->orWhere('no_telepon_plg', 'LIKE', '%' . $request->search . '%');
        }

        $pelangganof = $query->get();
        $totalJumlahPembayaranKeseluruhan = $query->sum('harga_paket');
        $totalPelangganKeseluruhan = $query->count();


        return view('pelangganof.index', compact('pelangganof', 'totalJumlahPembayaranKeseluruhan', 'totalPelangganKeseluruhan'));
    }



    public function create()
    {
        return view('pelangganof.create');
    }


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

        $pelangganof = new Pelangganof();
        $pelangganof->id_plg = $request->id_plg;
        $pelangganof->nama_plg = $request->nama_plg;
        $pelangganof->alamat_plg = $request->alamat_plg;
        $pelangganof->no_telepon_plg = $request->no_telepon_plg;
        $pelangganof->aktivasi_plg = $request->aktivasi_plg;
        $pelangganof->paket_plg = $request->paket_plg;
        $pelangganof->harga_paket = $request->harga_paket;
        $pelangganof->tgl_tagih_plg = $request->tgl_tagih_plg;

        $pelangganof->keterangan_plg = $request->keterangan_plg;
        $pelangganof->odp = $request->odp;
        $pelangganof->maps = $request->maps;

        $pelangganof->save();

        return redirect()->route('pelangganof.index');
    }


    public function show(string $id) {}


    public function edit(string $id_plg)
    {
        $pelangganof = Pelangganof::findOrFail($id_plg);
        return view('pelangganof.edit', compact('pelangganof'));
    }


    public function update(Request $request, string $id_plg)
    {
        $pelangganof = Pelangganof::findOrFail($id_plg);


        $pelangganof->id_plg = $request->id_plg;
        $pelangganof->nama_plg = $request->nama_plg;
        $pelangganof->alamat_plg = $request->alamat_plg;
        $pelangganof->no_telepon_plg = $request->no_telepon_plg;
        $pelangganof->aktivasi_plg = $request->aktivasi_plg;
        $pelangganof->paket_plg = $request->paket_plg;
        $pelangganof->harga_paket = $request->harga_paket;
        $pelangganof->status_pembayaran = $request->status_pembayaran;
        $pelangganof->keterangan_plg = $request->keterangan_plg ?? null;
        $pelangganof->odp = $request->odp;
        $pelangganof->tgl_tagih_plg = $request->tgl_tagih_plg;
        $pelangganof->longitude = $request->longitude;
        $pelangganof->latitude = $request->latitude;

        $pelangganof->save();

        return redirect()->route('pelangganof.index');
    }


    public function destroy(string $id_plg)
    {
        $pelangganof = Pelangganof::findOrFail($id_plg);
        $pelangganof->delete();

        return redirect()->route('pelangganof.index');
    }

    public function showOff($id)
    {
        // Ambil data pelangganof dari tabel pelangganof
        $pelangganof = Pelangganof::find($id);

        if ($pelangganof) {
            // Masukkan data ke tabel plg_of
            DB::table('pelanggan')->insert([
                'id_plg' => $pelangganof->id_plg,
                'nama_plg' => $pelangganof->nama_plg,
                'alamat_plg' => $pelangganof->alamat_plg,
                'no_telepon_plg' => $pelangganof->no_telepon_plg,
                'paket_plg' => $pelangganof->paket_plg,
                'harga_paket' => $pelangganof->harga_paket,
                'odp' => $pelangganof->odp,
                'keterangan_plg' => $pelangganof->keterangan_plg,
                'longitude' => $pelangganof->longitude,
                'latitude' => $pelangganof->latitude,
                'aktivasi_plg' => $pelangganof->aktivasi_plg,

                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Hapus data dari tabel pelangganof
            $pelangganof->delete();

            // Redirect ke halaman pelangganof dengan pesan sukses
            return redirect()->route('pelangganof.index')->with('success', 'Pelangganof berhasil dipindahkan ke tabel pelangganof off.');
        } else {
            return redirect()->route('pelangganof.index')->with('error', 'Pelangganof tidak ditemukan.');
        }
    }

    public function aktifkan_pelanggan1($id)
    {
        // Ambil data rekap pemasangan berdasarkan ID
        $pelangganof = Pelangganof::find($id);

        if (!$pelangganof) {
            return redirect()->back()->with('error', 'Data pemasangan tidak ditemukan.');
        }

        // Cek apakah pelanggan sudah ada di tabel `pelanggan`
        $existingPelanggan = Pelanggan::where('id_plg', $pelangganof->id_plg)->first();

        if ($existingPelanggan) {
            return redirect()->route('pelanggan.psb')->with('error', 'Pelanggan ini sudah diaktivasi.');
        }

        // Simpan data pelanggan baru
        $pelanggan = new Pelanggan();
        $pelanggan->id_plg = $pelangganof->id_plg;
        $pelanggan->nama_plg = $pelangganof->nama;
        $pelanggan->alamat_plg = $pelangganof->alamat;
        $pelanggan->no_telepon_plg = $pelangganof->no_telpon;
        $pelanggan->paket_plg = $pelangganof->paket_plg;
        $pelanggan->harga_paket = $pelangganof->harga_paket;
        $pelanggan->odp = $pelangganof->odp;
        $pelanggan->longitude = $pelangganof->longitude;
        $pelanggan->aktivasi_plg = $pelangganof->tgl_aktivasi;
        $pelanggan->latitude = $pelangganof->latitude;
        $pelanggan->tgl_tagih_plg = \Carbon\Carbon::now()->format('d'); // Tagih di hari ini
        // $pelanggan->tgl_tagih_plg = \Carbon\Carbon::parse($pelangganof->tgl_aktivasi)->format('d'); //ini tgl tagih pelanggan
        $pelanggan->status_pembayaran = 'reactivasi'; // Status awal PSB

        $pelanggan->save();

        return redirect()->route('rekap_pemasangan.index')->with('success', 'Pelanggan berhasil diaktivasi.');
    }

    public function aktifkan_pelanggan2($id)
    {
        // Ambil data pelanggan dari tabel pelangganof
        $pelangganof = Pelangganof::find($id);

        if ($pelangganof) {
            try {
                // Pastikan tanggal tagih diubah ke format yang sesuai jika perlu
                // $tglTagih = Carbon::createFromFormat('d/m/Y', $pelangganof->aktivasi_plg)->format('Y-m-d');

                // Masukkan data ke tabel pelanggan
                DB::table('pelanggan')->insert([
                    'id_plg' => $pelangganof->id_plg,
                    'nama_plg' => $pelangganof->nama_plg,
                    'alamat_plg' => $pelangganof->alamat_plg,
                    'no_telepon_plg' => $pelangganof->no_telepon_plg,
                    'aktivasi_plg' =>  $pelangganof->aktivasi_plg,  // Pastikan format tanggal sesuai
                    'paket_plg' => $pelangganof->paket_plg,
                    'harga_paket' => $pelangganof->harga_paket,
                    'odp' => $pelangganof->odp,
                    'keterangan_plg' => $pelangganof->keterangan_plg,
                    'longitude' => $pelangganof->longitude,
                    'latitude' => $pelangganof->latitude,
                    'tgl_tagih_plg' => $pelangganof->tgl_tagih_plg,
                    'status_pembayaran' => 'Reactivasi', // Tambahkan baris ini

                    'updated_at' => now(),

                ]);


                // Hapus data dari tabel pelangganof
                // $pelangganof->delete();

                // Redirect ke halaman pelanggan dengan pesan sukses
                return redirect()->route('pelangganof.index')->with('success', 'Pelanggan OFF berhasil di Aktifkan Kembali.');
            } catch (\Exception $e) {
                // Log error dan kembalikan pesan error jika terjadi masalah
                Log::error('Error aktifkan_pelanggan: ' . $e->getMessage());
                return redirect()->route('pelangganof.index')->with('error', 'Terjadi kesalahan saat memindahkan pelanggan.');
            }
        } else {
            return redirect()->route('pelangganof.index')->with('error', 'Pelanggan tidak ditemukan.');
        }
    }

    public function aktifkan_pelanggan($id)
    {
        $pelangganof = Pelangganof::find($id);

        if ($pelangganof) {
            try {
                // Format tanggal jika perlu
                //   $tglTagih = Carbon::createFromFormat('d/m/Y', $pelangganof->aktivasi_plg)->format('Y-m-d');

                // Log data sebelum insert
                Log::info('Data yang akan disimpan:', [
                    'id_plg' => $pelangganof->id_plg,
                    'nama_plg' => $pelangganof->nama_plg,
                    // Tambahkan variabel lain yang relevan
                ]);

                // Insert data ke tabel pelanggan
                DB::table('pelanggan')->insert([
                    'id_plg' => $pelangganof->id_plg,
                    'nama_plg' => $pelangganof->nama_plg,
                    'alamat_plg' => $pelangganof->alamat_plg,
                    'no_telepon_plg' => $pelangganof->no_telepon_plg,
                    'aktivasi_plg' =>  $pelangganof->aktivasi_plg,  // Pastikan format tanggal sesuai
                    'paket_plg' => $pelangganof->paket_plg,
                    'harga_paket' => $pelangganof->harga_paket,
                    'odp' => $pelangganof->odp,
                    'keterangan_plg' => $pelangganof->keterangan_plg,
                    'longitude' => $pelangganof->longitude,
                    'latitude' => $pelangganof->latitude,
                    'tgl_tagih_plg' => $pelangganof->tgl_tagih_plg,
                    'status_pembayaran' => 'Reactivasi', // Tambahkan baris ini

                    'updated_at' => now(),

                ]);

                // Hapus data dari tabel pelangganof jika perlu
                // $pelangganof->delete();

                return redirect()->route('pelangganof.index')->with('success', 'Pelanggan OFF berhasil diaktivasi kembali.');
            } catch (\Exception $e) {
                Log::error('Error aktifkan_pelanggan: ' . $e->getMessage());
                return redirect()->route('pelangganof.index')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
            }
        } else {
            return redirect()->route('pelangganof.index')->with('error', 'Pelanggan tidak ditemukan.');
        }
    }


    public function filterByTanggalTagihindex(Request $request)
    {
        // Ambil nilai filter dari request
        $status_pembayaran_display = $request->input('status_pembayaran', '');
        $tanggal_tagih = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $filter = $request->input('filter');
        $tanggal_pembayaran = $request->input('tanggal');

        // Mulai query dasar pada model Pelanggan
        $query = Pelangganof::query();

        // Filter berdasarkan status pembayaran (menggunakan strcasecmp untuk case-insensitive comparison)
        if ($status_pembayaran_display) {
            $query->whereRaw('strcasecmp(status_pembayaran, ?) = 0', [$status_pembayaran_display]);
        }

        // Filter berdasarkan tanggal tagih jika ada
        if ($tanggal_tagih) {
            $query->where('tgl_tagih_plg', $tanggal_tagih);
        }

        // Filter berdasarkan paket pelanggan jika ada
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan harga paket jika ada
        if ($harga_paket) {
            $query->where('harga_paket', $harga_paket);
        }

        // Filter status pembayaran: Sudah Bayar atau Belum Bayar
        if ($filter == 'sudah_bayar') {
            $query->whereNotNull('pembayaranTerakhir');
        } elseif ($filter == 'belum_bayar') {
            $query->whereNull('pembayaranTerakhir');
        }

        // Urutkan berdasarkan pembayaran terbaru atau terlama
        if ($filter == 'terbaru') {
            $query->orderBy('pembayaranTerakhir', 'desc');
        } elseif ($filter == 'terlama') {
            $query->orderBy('pembayaranTerakhir', 'asc');
        }

        // Filter berdasarkan tanggal pembayaran tertentu jika ada
        if ($tanggal_pembayaran) {
            $query->whereDate('pembayaranTerakhir', '=', $tanggal_pembayaran);
        }

        // Ambil data yang sudah difilter
        $items = $query->get();

        // Hitung jumlah item pada tanggal tertentu jika filter tanggal digunakan
        $jumlahPadaTanggal = null;
        if ($tanggal_pembayaran) {
            $jumlahPadaTanggal = $query->count();
        }

        // Lakukan pagination pada query dan tambahkan query string dari filter
        $pelanggan = $query->paginate(100)->appends($request->all());

        // Kembalikan data pelanggan ke view dengan filter
        return view('pelangganof.index', compact('items', 'jumlahPadaTanggal', 'pelangganof', 'harga_paket', 'paket_plg', 'tanggal_tagih', 'status_pembayaran_display', 'tanggal_pembayaran'));
    }
}
