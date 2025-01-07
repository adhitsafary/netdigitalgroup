<?php

namespace App\Http\Controllers;

use App\Models\BayarPelanggan;
use Illuminate\Http\Request;

class TanggalController extends Controller
{
    // Tampilkan halaman form
    public function index()
    {
        $records = BayarPelanggan::all(); // Mengambil semua data
        return view('ubah-tanggal.index', compact('records'));
    }

    // Proses update tanggal
    public function update(Request $request)
    {
        // Validasi input
        $request->validate([
            'id' => 'required|exists:nama_tabel,id', // Sesuaikan nama tabel
            'created_at' => 'required|date',
        ]);

        // Update tanggal
        $record = BayarPelanggan::findOrFail($request->id);
        $record->created_at = $request->created_at;
        $record->save();

        // Redirect kembali dengan pesan sukses
        return redirect()->route('ubah-tanggal.index')->with('success', 'Tanggal berhasil diperbarui.');
    }
}
