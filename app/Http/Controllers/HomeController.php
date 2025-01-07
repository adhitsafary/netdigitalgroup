<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function redirectToPelanggan()
    {
        // Mendapatkan tanggal saat ini
        $tanggalHariIni = Carbon::now()->day;

        // Redirect ke URL dengan parameter tgl_tagih_plg
        return redirect()->to('pelanggan?tgl_tagih_plg=' . $tanggalHariIni);
    }

    public function showPelangganBelumBayar()
    {
        // Mendapatkan tanggal hari ini (opsional)
        $tanggalHariIni = Carbon::now()->day;

        // Redirect ke URL dengan parameter status_pembayaran
        return redirect()->to('pelanggan?tgl_tagih_plg=' . $tanggalHariIni . '&paket_plg=&harga_paket=&status_pembayaran=belum_bayar');
    }

    public function showPelangganSudahBayar()
    {
        // Mendapatkan tanggal hari ini (opsional)
        $tanggalHariIni = Carbon::now()->day;

        // Redirect ke URL dengan parameter status_pembayaran
        return redirect()->to('pelanggan?tgl_tagih_plg=' . $tanggalHariIni . '&paket_plg=&harga_paket=&status_pembayaran=sudah_bayar');
    }

    public function historyhariini()
    {
        // Mendapatkan tanggal hari ini (opsional)
        $tanggalHariIni = Carbon::now()->day;

        // Redirect ke URL dengan parameter status_pembayaran
        return redirect()->to('pembayaran/filter?created_at='  . $tanggalHariIni);
    }

}

