<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Pelanggan;
use App\Models\Pelangganof;
use App\Models\PembayaranPelanggan;
use App\Models\Perbaikan;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */

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

    public function destroy(string $id)
    {
        // Temukan data berdasarkan ID otomatis
        $bayarPelanggan = BayarPelanggan::findOrFail($id);

        // Ambil id otomatis dan id_bawan (pelanggan_id)
        $idOtomatis = $bayarPelanggan->tanggal_pembayaran;
        $pelangganId = $bayarPelanggan->pelanggan_id; // Asumsikan kolom ini adalah id_bawan dari tabel pelanggan
        $pelanggan = Pelanggan::findOrFail($bayarPelanggan->pelanggan_id);


        // Hapus data
        $bayarPelanggan->delete();

        // Redirect ke halaman detail pelanggan dengan pesan sukses
      //  return redirect()->route('pelanggan.historypembayaran', $pelangganId) //ini yang langsung mengarah ke id yang di hapus diawal
      return redirect()->route('pembayaran.index')
            ->with('success', "Data pembayaran  $pelanggan->nama_plg, dengan ID: $idOtomatis telah dihapus.");
    }




    public function export2(Request $request, $format)
    {
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $search = $request->input('search');
        $untuk_pembayaran = $request->input('untuk_pembayaran');

        $pembayaran = BayarPelanggan::query()
            ->when($date_start && $date_end, function ($query) use ($date_start, $date_end) {
                return $query->whereBetween('created_at', [$date_start, $date_end]);
            })
            ->when($tgl_tagih_plg, function ($query) use ($tgl_tagih_plg) {
                return $query->where('tgl_tagih_plg', $tgl_tagih_plg);
            })
            ->when($paket_plg, function ($query) use ($paket_plg) {
                return $query->where('paket_plg', $paket_plg);
            })
            ->when($harga_paket, function ($query) use ($harga_paket) {
                return $query->where('harga_paket', $harga_paket);
            })
            ->when($search, function ($query) use ($search) {
                return $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nama_pelanggan', 'like', "%$search%")
                        ->orWhere('alamat', 'like', "%$search%")
                        ->orWhere('telepon', 'like', "%$search%");
                });
            })
            ->when($untuk_pembayaran, function ($query) use ($untuk_pembayaran) {
                return $query->where('untuk_pembayaran', $untuk_pembayaran);
            })
            ->get();

        if ($format === 'pdf') {
            $pdf = PDF::loadView('pembayaran.pdf', ['pembayaran' => $pembayaran]);
            return $pdf->download('bayar_pelanggan_' . now()->format('Y-m-d') . '.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new PembayaranExport($pembayaran), 'bayar_pelanggan_' . now()->format('Y-m-d') . '.xlsx');
        }
    }


    public function export(Request $request, $format)
    {
        $query = BayarPelanggan::query();

        // Filter data berdasarkan bulan dan tahun sekarang
        $query->whereMonth('tanggal_pembayaran', now()->month)
            ->whereYear('tanggal_pembayaran', now()->year);

        // Tambahkan filter tambahan jika diperlukan
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $search = $request->input('search');
        $untuk_pembayaran = $request->input('untuk_pembayaran');

        $query->when($date_start && $date_end, function ($query) use ($date_start, $date_end) {
            return $query->whereBetween('tanggal_pembayaran', [$date_start, $date_end]);
        })
        ->when($tgl_tagih_plg, function ($query) use ($tgl_tagih_plg) {
            return $query->where('tgl_tagih_plg', $tgl_tagih_plg);
        })
        ->when($paket_plg, function ($query) use ($paket_plg) {
            return $query->where('paket_plg', $paket_plg);
        })
        ->when($harga_paket, function ($query) use ($harga_paket) {
            return $query->where('harga_paket', $harga_paket);
        })
        ->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
                $subQuery->where('nama_pelanggan', 'like', "%$search%")
                    ->orWhere('alamat', 'like', "%$search%")
                    ->orWhere('telepon', 'like', "%$search%");
            });
        })
        ->when($untuk_pembayaran, function ($query) use ($untuk_pembayaran) {
            return $query->where('untuk_pembayaran', $untuk_pembayaran);
        });

        $pembayaran = $query->get();

        if ($format === 'pdf') {
            $pdf = PDF::loadView('pembayaran.pdf', ['pembayaran' => $pembayaran]);
            return $pdf->download('bayar_pelanggan_' . now()->format('Y-m-d') . '.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new PembayaranExport($pembayaran), 'bayar_pelanggan_' . now()->format('Y-m-d') . '.xlsx');
        }
    }

    public function export22(Request $request, $format)
    {
        // Mulai query
        $query = BayarPelanggan::query();

        // Ambil filter dari request
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');
        $tgl_tagih_plg = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $harga_paket = $request->input('harga_paket');
        $search = $request->input('search');
        $untuk_pembayaran = $request->input('untuk_pembayaran');

        // Terapkan filter jika ada
        $query->when($date_start && $date_end, function ($query) use ($date_start, $date_end) {
            return $query->whereBetween('tanggal_pembayaran', [$date_start, $date_end]);
        })
        ->when($tgl_tagih_plg, function ($query) use ($tgl_tagih_plg) {
            return $query->where('tgl_tagih_plg', $tgl_tagih_plg);
        })
        ->when($paket_plg, function ($query) use ($paket_plg) {
            return $query->where('paket_plg', $paket_plg);
        })
        ->when($harga_paket, function ($query) use ($harga_paket) {
            return $query->where('harga_paket', $harga_paket);
        })
        ->when($search, function ($query) use ($search) {
            return $query->where(function ($subQuery) use ($search) {
                $subQuery->where('nama_pelanggan', 'like', "%$search%")
                    ->orWhere('alamat', 'like', "%$search%")
                    ->orWhere('telepon', 'like', "%$search%");
            });
        })
        ->when($untuk_pembayaran, function ($query) use ($untuk_pembayaran) {
            return $query->where('untuk_pembayaran', $untuk_pembayaran);
        });

        // Ambil data hasil query
        $pembayaran = $query->get();

        // Ekspor data sesuai format
        if ($format === 'pdf') {
            $pdf = PDF::loadView('pembayaran.pdf', ['pembayaran' => $pembayaran]);
            return $pdf->download('bayar_pelanggan_' . now()->format('Y-m-d') . '.pdf');
        } elseif ($format === 'excel') {
            return Excel::download(new PembayaranExport($pembayaran), 'bayar_pelanggan_' . now()->format('Y-m-d') . '.xlsx');
        }
    }






    public function index(Request $request)
    {
        // Ambil nilai filter dari request
        $status_pembayaran_display = $request->input('status_pembayaran', '');
        $tanggal = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran');
        $tanggal_pembayaran = $request->input('tanggal_pembayaran');
        $created_at = $request->input('created_at');
        $bulan = $request->input('bulan');
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');
        $search = $request->input('search');
        $untuk_pembayaran = $request->input('untuk_pembayaran');

        // Mulai query
        $query = BayarPelanggan::query();

        $query->orderBy('created_at', 'desc');

        // Filter berdasarkan status pembayaran
        if ($status_pembayaran_display) {
            $query->where('status_pembayaran', $status_pembayaran_display);
        }

        // Filter berdasarkan tanggal tagih
        if ($tanggal) {
            $query->where('tgl_tagih_plg', $tanggal);
        }

        // Filter berdasarkan paket pelanggan
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan jumlah pembayaran
        if ($jumlah_pembayaran) {
            $query->where('jumlah_pembayaran', $jumlah_pembayaran);
        }

        // Filter berdasarkan tanggal pembayaran (format Y-m-d)
        if ($created_at) {
            $query->whereDate('created_at', $created_at);
        }

        // Filter berdasarkan bulan
        if ($bulan) {
            $query->whereMonth('tanggal_pembayaran', $bulan);
        } elseif ($date_start && $date_end) {
            // Filter berdasarkan rentang tanggal
            $query->whereBetween('tanggal_pembayaran', [$date_start, $date_end]);
        } else {
            // Default: hanya data bulan ini
            $query->whereMonth('tanggal_pembayaran', Carbon::now()->month)
                ->whereYear('tanggal_pembayaran', Carbon::now()->year);
        }

        // Filter berdasarkan pencarian
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('metode_transaksi', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan "untuk pembayaran"
        if ($untuk_pembayaran) {
            $query->where('untuk_pembayaran', $untuk_pembayaran);
        }

        // Ambil hasil query
        //$pembayaran = $query->paginate(100);
        $pembayaran = $query->paginate(1500)->appends($request->all());

        // Hitung total jumlah pembayaran
        $totalJumlahPembayaran = $query->sum('jumlah_pembayaran');

        // Hitung total pelanggan
        $totalPelanggan = $query->count();

        // Kembalikan data ke view
        return view('pembayaran.index', compact(
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
            'untuk_pembayaran',
        ));
    }

    public function pembayaran_hp(Request $request)
    {
        // Ambil nilai filter dari request
        $status_pembayaran_display = $request->input('status_pembayaran', '');
        $tanggal = $request->input('tgl_tagih_plg');
        $paket_plg = $request->input('paket_plg');
        $jumlah_pembayaran = $request->input('jumlah_pembayaran');
        $tanggal_pembayaran = $request->input('tanggal_pembayaran');
        $created_at = $request->input('created_at');
        $bulan = $request->input('bulan');
        $date_start = $request->input('date_start');
        $date_end = $request->input('date_end');
        $search = $request->input('search');
        $untuk_pembayaran = $request->input('untuk_pembayaran');

        // Mulai query
        $query = BayarPelanggan::query();

        $query->orderBy('created_at', 'desc');

        // Filter berdasarkan status pembayaran
        if ($status_pembayaran_display) {
            $query->where('status_pembayaran', $status_pembayaran_display);
        }

        // Filter berdasarkan tanggal tagih
        if ($tanggal) {
            $query->where('tgl_tagih_plg', $tanggal);
        }

        // Filter berdasarkan paket pelanggan
        if ($paket_plg) {
            $query->where('paket_plg', $paket_plg);
        }

        // Filter berdasarkan jumlah pembayaran
        if ($jumlah_pembayaran) {
            $query->where('jumlah_pembayaran', $jumlah_pembayaran);
        }

        // Filter berdasarkan tanggal pembayaran (format Y-m-d)
        if ($created_at) {
            $query->whereDate('created_at', $created_at);
        }

        // Filter berdasarkan bulan
        if ($bulan) {
            $query->whereMonth('created_at', $bulan);
        } elseif ($date_start && $date_end) {
            // Filter berdasarkan rentang tanggal
            $query->whereBetween('created_at', [$date_start, $date_end]);
        } else {
            // Default: hanya data bulan ini
            $query->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year);
        }

        // Filter berdasarkan pencarian
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('id_plg', $search)
                    ->orWhere('nama_plg', 'like', "%{$search}%")
                    ->orWhere('alamat_plg', 'like', "%{$search}%")
                    ->orWhere('no_telepon_plg', 'like', "%{$search}%")
                    ->orWhere('metode_transaksi', 'like', "%{$search}%");
            });
        }

        // Filter berdasarkan "untuk pembayaran"
        if ($untuk_pembayaran) {
            $query->where('untuk_pembayaran', $untuk_pembayaran);
        }

        // Ambil hasil query
        //$pembayaran = $query->paginate(100);
        $pembayaran = $query->paginate(1500)->appends($request->all());

        // Hitung total jumlah pembayaran
        $totalJumlahPembayaran = $query->sum('jumlah_pembayaran');

        // Hitung total pelanggan
        $totalPelanggan = $query->count();

        // Kembalikan data ke view
        return view('pembayaran.pembayaran_hp', compact(
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
            'untuk_pembayaran',
        ));
    }




    public function edit(string $id_plg)
    {
        $pembayaran = BayarPelanggan::findOrFail($id_plg);
        return view('pembayaran.edit', compact('pembayaran'));
    }

    public function update(Request $request, string $id_plg)
    {
        // Validasi input
        $validatedData = $request->validate([
            'paket_plg' => 'required|string|max:255',
            'jumlah_pembayaran' => 'required|numeric',
            'metode_transaksi' => 'required|string',
            'keterangan_plg' => 'nullable|string',
            'created_at' => 'required|date_format:Y-m-d\TH:i',
            'tanggal_pembayaran' => 'required|date_format:Y-m',
        ]);

        // Ambil data pelanggan yang sudah ada
        $pembayaran = BayarPelanggan::findOrFail($id_plg);

        // Update data
        $pembayaran->paket_plg = $validatedData['paket_plg'];
        $pembayaran->jumlah_pembayaran = $validatedData['jumlah_pembayaran'];
        $pembayaran->metode_transaksi = $validatedData['metode_transaksi'];
        $pembayaran->keterangan_plg = $validatedData['keterangan_plg'];

        // Pastikan waktu dalam format Y-m-d H:i:s
        $pembayaran->tanggal_pembayaran = Carbon::createFromFormat('Y-m', $validatedData['tanggal_pembayaran'])->startOfMonth()->format('Y-m-d');


        $pembayaran->created_at = Carbon::parse($validatedData['created_at'])->format('Y-m-d H:i:s');

        // Simpan data yang sudah diperbarui
        $pembayaran->save();

        return redirect()->route('pembayaran.index')->with('success', 'Data pembayaran berhasil diperbarui');
    }
}
