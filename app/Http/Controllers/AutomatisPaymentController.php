<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class AutomatisPaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q'); // Input dari pencarian

        // Jika tidak ada input pencarian, kembalikan koleksi kosong
        $pelanggan = collect();

        // Jika ada input pencarian, lakukan query ke database
        if ($query) {
            $pelanggan = Pelanggan::with('pembayaran')
                ->where('id_plg', $query)
                ->orWhere('nama_plg', 'LIKE', "%$query%")
                ->paginate(10);
        }

        return view('automatispayment.index', compact('pelanggan', 'query'));
    }

    public function payment($id)
    {
        $pelanggan = Pelanggan::with('pembayaran')->findOrFail($id);

        // Ambil pembayaran terakhir
        $lastPayment = $pelanggan->pembayaran->last();
        $lastMonth = $lastPayment ? $lastPayment->tanggal_pembayaran : 'Belum Ada Pembayaran';

        return view('automatispayment.payment', compact('pelanggan', 'lastMonth'));
    }

    public function processPayment(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);

        // Generate kode unik 3 digit
        $kodeUnik = rand(100, 999);

        // Hitung total pembayaran
        $totalPembayaran = $pelanggan->harga_paket + $kodeUnik;

        // Simpan pembayaran ke tabel 'bayar_pelanggan'
        $pelanggan->pembayaran()->create([
            'tanggal_pembayaran' => now(),
            'kode_unik' => $kodeUnik,
        ]);

        return redirect()->route('automatispayment.index')->with('success', 'Pembayaran berhasil! Total: ' . number_format($totalPembayaran, 0, ',', '.'));
    }
}
