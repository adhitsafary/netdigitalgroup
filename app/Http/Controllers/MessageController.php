<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function create(Request $request)
    {
        //$query = Pelanggan::query();

        $query = Pelanggan::whereNotIn('status_pembayaran', ['Sudah Bayar', 'Block', 'Isolir']);


        // Menambahkan filter berdasarkan input dari pengguna
        if ($request->filled('search')) {
            $query->where('nama_plg', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('alamat_plg')) {
            $query->where('alamat_plg', 'like', '%' . $request->alamat_plg . '%');
        }

        if ($request->filled('tgl_tagih_plg')) {
            $query->where('tgl_tagih_plg', $request->tgl_tagih_plg);
        }

        // Ambil data pelanggan yang telah difilter
        $pelanggan = $query->get(['id_plg', 'nama_plg', 'no_telepon_plg', 'tgl_tagih_plg']);

        return view('whatsapp.send-message', compact('pelanggan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'target' => 'required|array'
        ]);

        $token = "BwoxV6XB9KcUPaEhCrB4";
        $targetNumbers = $request->input('target'); // Array of target numbers

        try {
            foreach ($targetNumbers as $target) {
                // Ambil data pelanggan berdasarkan nomor telepon
                $pelanggan = Pelanggan::where('no_telepon_plg', $target)->first();

                if ($pelanggan) {
                    // Konversi tgl_tagih_plg yang berupa angka ke dalam format tanggal yang sesuai
                    $tglTagihPlg = now()->setDay($pelanggan->tgl_tagih_plg); // Asumsi angka tersebut adalah hari
                    $formattedDate = $tglTagihPlg->format('d F Y'); // Format tanggal tagihan

                    // Tentukan paket dan kecepatannya berdasarkan nilai paket_plg
                    $paket = '';
                    switch ($pelanggan->paket_plg) {
                        case 1:
                            $paket = '5 Mbps';
                            break;
                        case 2:
                            $paket = '10 Mbps';
                            break;
                        case 3:
                            $paket = '15 Mbps';
                            break;
                        case 4:
                            $paket = '25 Mbps';
                            break;

                        default:
                            $paket = 'Paket tidak diketahui';
                            break;
                    }

                    // Siapkan pesan
                    $message = "*REMINDER🙏🏻*\n";
                    $message .= "*NET | NET. DIGITAL-WiFi*\n\n";
                    $message .= "*Pelanggan YTH:*\n";
                    $message .= "*{$pelanggan->nama_plg} - {$pelanggan->alamat_plg}*\n\n";
                    $message .= "*PEMBERITAHUAN*\n";
                    $message .= "Tagihan Bulan : " . now()->format('F Y') . "\n";
                    $message .= "Jenis Paket : {$paket}\n"; // Masukkan jenis paket yang telah ditentukan
                    $message .= "Biaya Paket : Rp. {$pelanggan->harga_paket}\n";
                    $message .= "Biaya  : Rp. 0 \n";
                    $message .= "PPN 0% : Rp. 0\n";
                    $message .= "Diskon : Rp. 0\n";
                    $message .= "*Total Besar Tagihan + PPN : Rp. {$pelanggan->harga_paket}*\n";
                    $message .= "Masa aktif s/d {$formattedDate}\n";
                    $message .= "Ket : *BELUM TERBAYAR*\n\n";
                    $message .= "PEMBAYARAN:\n";

                    $message .= "- via transfer : rek BCA : 3770198576 atas nama *Ruslandi* \n";
                    $message .= "- *Pembayaran Via Penjemputan/Pickup dikenakan biaya jasa pengambilan Rp.5000*\n\n";
                    $message .= "Dimohon untuk Melampirkan bukti pembayaran apabila sudah melakukan pembayaran.\n\n";

                    $message .= "INFO TAMBAHAN:\n";
                    $message .= "*Apabila telat melakukan pembayaran iuran wifi akan dikenakan pemutusan sementara🔊.* \n\n";

                    $message .= "Admin + CS     : 0857-9392-0206 (Agisna 🧕🏻)\n";
                    $message .= "marketing      : 0857-2222-0169 (Gilang 👳🏻‍♂️)\n";
                    $message .= "🙏🏻";

                    // Kirim pesan
                    $response = Http::withHeaders([
                        'Authorization' => $token,
                    ])->asForm()->post('https://api.fonnte.com/send', [
                        'target' => $target,
                        'message' => $message,
                        'delay' => '5',
                    ]);

                    if (!$response->successful()) {
                        return back()->withErrors('Gagal mengirim pesan: ' . $response->body());
                    }
                }
            }

            return back()->with('status', 'Pesan berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function create2(Request $request)
    {
        $query = Pelanggan::whereNotIn('status_pembayaran', ['Sudah Bayar', 'Block', 'Isolir']);

        if ($request->filled('search')) {
            $query->where('nama_plg', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('alamat_plg')) {
            $query->where('alamat_plg', 'like', '%' . $request->alamat_plg . '%');
        }

        if ($request->filled('tgl_tagih_plg')) {
            $query->where('tgl_tagih_plg', $request->tgl_tagih_plg);
        }

        $pelanggan = $query->get(['id_plg', 'nama_plg', 'no_telepon_plg', 'tgl_tagih_plg', 'alamat_plg', 'paket_plg']);

        return view('whatsapp.peringatan', compact('pelanggan'));
    }

    public function store2(Request $request)
    {
        $request->validate([
            'target' => 'required|array'
        ]);

        $token = "BwoxV6XB9KcUPaEhCrB4";
        $targetNumbers = $request->input('target');

        try {
            foreach ($targetNumbers as $target) {
                $pelanggan = Pelanggan::where('no_telepon_plg', $target)->first();

                if ($pelanggan) {
                    $tglTagihPlg = now()->setDay($pelanggan->tgl_tagih_plg);
                    $formattedDate = $tglTagihPlg->format('d F Y');

                    $paket = match ($pelanggan->paket_plg) {
                        1 => '5 Mbps',
                        2 => '10 Mbps',
                        3 => '15 Mbps',
                        4 => '25 Mbps',
                        default => 'Paket tidak diketahui',
                    };

                    $message = "Assalamualaikum selamat siang. \n";
                    $message .= "Bapak / ibu kami dari net net, untuk menghindari isolir pembayaran bulanan \n";
                    $message .= "nya bisa di bayar hari ini?\n";
                    $message .= "Bisa lewat transfer atau dana .🙏🏻\n";

                    $response = Http::withHeaders([
                        'Authorization' => $token,
                    ])->asForm()->post('https://api.fonnte.com/send', [
                        'target' => $target,
                        'message' => $message,
                        'delay' => '5',
                    ]);

                    if (!$response->successful()) {
                        return back()->withErrors('Gagal mengirim pesan: ' . $response->body());
                    }
                }
            }

            return back()->with('status', 'Pesan berhasil dikirim!');
        } catch (\Exception $e) {
            return back()->withErrors('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
