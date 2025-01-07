<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use Illuminate\Http\Request;

class CobaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pelanggan.create');
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
            'odp' => 'required',
        ]);

        $pelanggan = new Pelanggan();
        $pelanggan->id_plg = $request->id_plg;
        $pelanggan->nama_plg = $request->nama_plg;
        $pelanggan->alamat_plg = $request->alamat_plg;
        $pelanggan->no_telepon_plg = $request->no_telepon_plg;
        $pelanggan->aktivasi_plg = $request->aktivasi_plg;
        $pelanggan->paket_plg = $request->paket_plg;
        $pelanggan->harga_paket = $request->harga_paket;
        $pelanggan->keterangan_plg = $request->keterangan_plg;
        $pelanggan->odp = $request->odp;
        $pelanggan->longitude = $request->longitude;
        $pelanggan->latitude = $request->latitude;

        // Set status pembayaran awal sebagai 'belum bayar'
        $pelanggan->status_pembayaran = 'belum bayar';

        $pelanggan->save();

        return redirect()->route('pelanggan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
