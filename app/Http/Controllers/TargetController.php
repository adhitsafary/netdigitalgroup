<?php

namespace App\Http\Controllers;

use App\Models\Target;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TargetController extends Controller
{
    public function index(Request $request)
    {
        // Ambil nama target dari input form (jika ada)
        $namaTarget = $request->input('nama_target');

        // Query target berdasarkan nama target jika ada, jika tidak ambil semua target
        if ($namaTarget) {
            $targets = Target::where('nama_target', $namaTarget)->get();
        } else {
            $targets = Target::all();
        }

        foreach ($targets as $target) {
            $currentDate = Carbon::now();
            $lastUpdate = Carbon::parse($target->last_update ?? $target->created_at);
            $daysPassed = $lastUpdate->diffInDays($currentDate);

            if ($daysPassed > 0) {
                $target->hari_tersisa -= $daysPassed;
                $target->last_update = $currentDate;
                $target->save();
            }
        }

        return view('target_pelanggan.index', compact('targets', 'namaTarget'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'nama_target' => 'required|string|max:255',
            'target' => 'required|integer',
            'hari' => 'required|integer',
        ]);

        Target::create([
            'nama_target' => $request->nama_target,
            'jumlah_target' => $request->target,
            'jumlah_hari' => $request->hari,
            'sisa_target' => $request->target,
            'hari_tersisa' => $request->hari,
            'last_update' => now(),
        ]);

        return redirect()->route('target.index')->with('success', 'Target berhasil disimpan.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'target_update' => 'required|integer',
        ]);

        // Ambil data target berdasarkan ID
        $target = Target::find($id);

        // Pastikan target ada sebelum melakukan pembaruan
        if ($target && $target->hari_tersisa > 0) {
            $target->sisa_target -= $validated['target_update'];

            // Simpan tanggal update terakhir
            $target->last_update = Carbon::now();
            $target->save();

            // Ambil nama_target untuk ditampilkan dalam pesan sukses
            $namaTarget = $target->nama_target;

            return redirect('/target')->with('success', "Target '{$namaTarget}' berhasil diperbarui.");
        }

        return redirect('/target')->with('error', 'Target tidak ditemukan atau hari tersisa telah habis.');
    }

    public function destroy($id)
    {
        // Mencari target berdasarkan ID
        $target = Target::find($id);

        // Memastikan target ditemukan
        if ($target) {
            $target->delete(); // Menghapus target dari database
            return redirect()->route('target.index')->with('success', 'Target berhasil dihapus.');
        }

        return redirect()->route('target.index')->with('error', 'Target tidak ditemukan.');
    }
}
