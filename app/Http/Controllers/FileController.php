<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{


    public function index(Request $request)
    {
        $query = File::query();

        // Filter berdasarkan kolom input dari request
        $category = $request->get('category');
        $file_name = $request->input('file_name');
        $file_size = $request->input('file_size');
        $created_at = $request->input('created_at');
        $updated_at = $request->input('updated_at');

        // Filter berdasarkan file_name
        if ($file_name) {
            $query->where('file_name', 'like', "%{$file_name}%");
        }

        // Filter berdasarkan file_size
        if ($file_size) {
            $query->where('file_size', $file_size);
        }

        // Filter berdasarkan created_at dan updated_at
        if ($created_at) {
            $query->whereDate('created_at', $created_at);
        }
        if ($updated_at) {
            $query->whereDate('updated_at', $updated_at);
        }

        // Filter berdasarkan category
        if ($category) {
            $query->where('category', $category);
        }

        // Filter pencarian umum
        $search = $request->input('search');
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('file_name', 'like', "%{$search}%");
            });
        }

        // Eksekusi query dengan filter
        $files = $query->get();

        return view('files.index', compact('files'));
    }




    public function index1(Request $request)
    {
        // Cek apakah ada permintaan untuk membuat folder baru
        if ($request->has('new_folder_name')) {
            $newFolderName = $request->input('new_folder_name');
            $folderPath = 'custom_folder/' . $newFolderName;

            // Membuat folder baru jika belum ada
            if (!Storage::exists($folderPath)) {
                Storage::makeDirectory($folderPath);
                return redirect()->back()->with('success', 'Folder baru berhasil dibuat: ' . $newFolderName);
            } else {
                return redirect()->back()->with('error', 'Folder dengan nama yang sama sudah ada.');
            }
        }

        // Inisialisasi query untuk mendapatkan file
        $query = File::query();

        // Tambahkan filter berdasarkan kolom input dari request
        $category = $request->get('category');
        $file_name = $request->input('file_name');
        $file_size = $request->input('file_size');
        $created_at = $request->input('created_at');
        $updated_at = $request->input('updated_at');

        // Filter berdasarkan file_name
        if ($file_name) {
            $query->where('file_name', 'like', "%{$file_name}%");
        }

        // Filter berdasarkan file_size
        if ($file_size) {
            $query->where('file_size', $file_size);
        }

        // Filter berdasarkan created_at dan updated_at
        if ($created_at) {
            $query->whereDate('created_at', $created_at);
        }
        if ($updated_at) {
            $query->whereDate('updated_at', $updated_at);
        }

        // Filter berdasarkan category
        if ($category) {
            $query->where('category', $category);
        }

        // Eksekusi query untuk mendapatkan daftar file
        $files = $query->get();

        // Ambil daftar folder dari direktori custom_folder
        $folders = collect(Storage::directories('custom_folder'))->map(function ($folder) {
            return (object)[
                'file_name' => basename($folder),
                'file_size' => null,
                'is_folder' => true
            ];
        });

        // Gabungkan folder dan file ke dalam satu koleksi
        $items = $folders->merge($files);

        return view('files.index', compact('items'));
    }


    // Menampilkan form untuk mengupload file baru
    public function create()
    {
        return view('files.create');
    }

    public function store(Request $request)
    {
        // Validasi file
        $request->validate([
            'files.*' => 'required|file',
            'file_names.*' => 'nullable|string', // Nama file opsional (kalau tidak ada, pakai nama asli)
        ]);

        // Iterasi untuk setiap file yang di-upload
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $index => $file) {
                // Ambil nama file yang dimasukkan user atau gunakan nama asli jika kosong
                $fileName = $request->file_names[$index] ?: $file->getClientOriginalName();

                // Simpan setiap file ke folder uploads
                $filePath = $file->storeAs('uploads', $fileName . '.' . $file->getClientOriginalExtension(), 'public');

                // Simpan informasi file ke database
                $fileModel = new File();
                $fileModel->file_name = $fileName; // Nama file yang diedit atau nama asli
                $fileModel->file_path = $filePath;
                $fileModel->file_size = $file->getSize();
                $fileModel->save();
            }
        }

        return redirect()->route('file.index')->with('success', 'Files uploaded successfully!');
    }


    // Menampilkan form untuk mengedit file
    public function edit($id)
    {
        $file = File::findOrFail($id);
        return view('files.edit', compact('file'));
    }

    public function update(Request $request, $id)
    {
        $file = File::findOrFail($id);

        // Validasi input
        $request->validate([
            'file_name' => 'required|string|max:255',
            'file' => 'nullable|file|max:10240', // Maksimum ukuran file 10MB
        ]);

        // Update nama file
        $file->file_name = $request->input('file_name');

        // Jika ada file baru yang diunggah
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            Storage::delete($file->file_path);

            // Unggah file baru dan update path
            $newFilePath = $request->file('file')->store('uploads');
            $file->file_path = $newFilePath;
            $file->file_size = $request->file('file')->getSize();
        }

        // Simpan perubahan
        $file->save();

        return redirect()->route('file.index')->with('success', 'File updated successfully!');
    }

    // Menghapus file
    public function destroy($id)
    {
        $file = File::findOrFail($id);

        // Hapus file dari storage
        Storage::disk('public')->delete($file->file_path);

        // Hapus data dari database
        $file->delete();

        return redirect()->route('file.index')->with('success', 'File deleted successfully');
    }

    public function download($id)
    {
        // Cari file berdasarkan ID
        $file = File::findOrFail($id);

        // Ambil path file dari storage
        $filePath = storage_path('app/public/' . $file->file_path);

        // Periksa apakah file ada
        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        // Return response download
        return response()->download($filePath, $file->file_name);
    }
}
