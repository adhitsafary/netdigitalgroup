<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PelangganBayarSendiriController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        Log::info("Search input: " . $search); // Cek apakah input terambil


        if ($search) {
            $pelanggan = Pelanggan::where('id_plg', 'LIKE', "%$search%")->get();
        }



        return view('pembayaran.csbayar', compact('pelanggan'));
    }



}
