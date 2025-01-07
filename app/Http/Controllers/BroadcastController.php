<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BroadcastController extends Controller
{
    public function index()
    {
        return view('whatsapp.broadcast');
    }

    public function send(Request $request)
    {
        $message = $request->input('message');
        $pelanggan = Pelanggan::all(); // Mengambil semua data pelanggan

        foreach ($pelanggan as $p) {
            // Mengirim pesan menggunakan API WhatsApp
            $response = Http::post('https://api.whatsapp.com/send', [
                'phone' => $p->no_telepon_plg,
                'text' => $message,
            ]);

            // Log atau handling error bisa ditambahkan di sini
        }

        return redirect()->route('broadcast.index')->with('success', 'Pesan broadcast telah dikirim.');
    }
}
