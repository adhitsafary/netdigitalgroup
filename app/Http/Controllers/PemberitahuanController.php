<?php

namespace App\Http\Controllers;

use App\Models\Pemberitahuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;


class PemberitahuanController extends Controller
{
    public function index()
    {
        $pemberitahuan = Pemberitahuan::all();
        return view('pemberitahuan.index', compact('pemberitahuan'));
    }

    public function create()
    {
        return view('pemberitahuan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pesan' => 'required|string',
        ]);

        $adminName = Auth::user() ? Auth::user()->name : 'Unknown Admin';

        $telegram_bot = Pemberitahuan::create([
            'nama' => $adminName,  // Mengisi nama admin
            'pesan' => $request->input('pesan'),
        ]);

        //$this->sendTelegramNotification($telegram_bot);

        return redirect()->route('pemberitahuan.index')->with('success', 'Pemberitahuan berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $pemberitahuan = Pemberitahuan::findOrFail($id);
        return view('pemberitahuan.edit', compact('pemberitahuan'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'pesan' => 'required|string',
        ]);

        $pemberitahuan = Pemberitahuan::findOrFail($id);
        $pemberitahuan->update($request->all());

        return redirect()->route('pemberitahuan.index')->with('success', 'Pemberitahuan berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $pemberitahuan = Pemberitahuan::findOrFail($id);
        $pemberitahuan->delete();

        return redirect()->route('pemberitahuan.index')->with('success', 'Pemberitahuan berhasil dihapus.');
    }


    private function sendTelegramNotification($telegram_bot)
    {
        $token = '7085351448:AAErPRbIkJJOwkDTIMFUlwNU3AN_UQ1cRkY';
        $chat_id = '5985430823';
        $url = "https://api.telegram.org/bot{$token}/sendMessage";



        // Menyiapkan pesan untuk Telegram
        $message =
            "💰 *Notifikasi Pembayaran Baru*\n" .
            "========================\n" .
            "👤 *Pelanggan :* {$telegram_bot->adminName}\n" .
            "📝 *Alamat :* {$telegram_bot->pesan}\n" .
            "=========================\n" .


        $client = new Client();

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
}
