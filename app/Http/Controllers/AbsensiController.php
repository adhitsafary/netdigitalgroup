<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

use App\Models\Absensi;
use App\Models\Karyawan;
use App\Models\KaryawanModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;




class AbsensiController extends Controller
{
    public function store2(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'status_hadir' => 'required|in:hadir,tidak hadir',
        ]);

        Absensi::create($request->all());

        return response()->json(['message' => 'Absensi berhasil ditambahkan']);
    }

    public function hitungGaji($user_id)
    {
        $user = User::findOrFail($user_id); // Ambil data user
        $nama_user = $user->name; // Ambil nama dari tabel user

        $total_absensi = Absensi::where('user_id', $user_id)->count();
        $tidak_hadir = Absensi::where('user_id', $user_id)->where('status_hadir', 'tidak hadir')->count();

        // Misalnya pengurangan gaji per hari absen
        $potongan_per_hari = 50000; // Sesuaikan
        $gaji_bersih = $user->karyawan->gaji - ($tidak_hadir * $potongan_per_hari); // Ambil gaji dari relasi Karyawan

        return response()->json([
            'karyawan' => $nama_user, // Menggunakan nama dari tabel user
            'total_hadir' => $total_absensi - $tidak_hadir,
            'total_tidak_hadir' => $tidak_hadir,
            'gaji_bersih' => $gaji_bersih,
        ]);
    }


    public function showAbsensiForm()
    {
        $users = User::with('karyawan')->get();
        return view('absensi.index', compact('users'));
    }


    public function showGajiForm(Request $request)
    {
        $users = User::with('karyawan')->get();
        $gaji = null;

        if ($request->has('user_id')) {
            $karyawan = KaryawanModel::where('id', $request->user_id)->firstOrFail();
            $tidak_hadir = Absensi::where('user_id', $request->user_id)->where('status_hadir', 'tidak hadir')->count();
            $potongan_per_hari = 50000; // Sesuaikan
            $gaji = [
                'karyawan' => $karyawan->nama,
                'total_hadir' => Absensi::where('user_id', $request->user_id)->count() - $tidak_hadir,
                'total_tidak_hadir' => $tidak_hadir,
                'gaji_bersih' => $karyawan->gaji - ($tidak_hadir * $potongan_per_hari),
            ];
        }

        return view('gaji', compact('users', 'gaji'));
    }

    public function splash()
    {
        return view('absensi.splash');
    }


    public function absen()
    {
        return view('absensi.absen');
    }




    public function index()
    {
        return view('absensi.login'); // Ganti dengan view login Anda
    }

    public function login(Request $request)
    {
        $request->validate(
            [
                'email' => 'required',
                'password' => 'required'
            ],
            [
                'email.required' => 'Email Wajib diisi',
                'password.required' => 'Password wajib diisi',
            ]
        );

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Pengalihan berdasarkan role
            return $this->redirectUser();
        } else {
            return redirect()->back()
                ->withErrors('Username dan Password yang dimasukkan tidak sesuai')
                ->withInput();
        }
    }

    private function redirectUser()
    {
        $role = Auth::user()->role;

        // Redirect sesuai dengan role
        switch ($role) {
            case 'teknisi':
                return redirect()->route('absensi.absen');
            case 'admin':
                return redirect()->route('absensi.absen');
            case 'superadmin':
                return redirect()->route('absensi.absen');
            default:
                return redirect()->route('absensi.absen'); // Redirect ke halaman login jika role tidak dikenal
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }



    public function store22(Request $request)
    {
        try {
            // Log data yang diterima
            Log::info('Data diterima dari frontend:', $request->all());

            // Validasi data
            $validated = $request->validate([
                'foto' => 'required|string',
                'titik_lokasi' => 'required|string',
            ]);

            // Proses simpan file foto
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->foto));

            // Debug: Log panjang image data
            Log::info('Panjang data gambar setelah decode: ', ['length' => strlen($imageData)]);

            $filename = time() . '.png';
            $folderPath = public_path('uploads');

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            // Pastikan folder bisa ditulis
            if (!is_writable($folderPath)) {
                Log::error('Folder uploads tidak dapat ditulis:', ['folder' => $folderPath]);
                return response()->json(['message' => 'Server tidak dapat menyimpan file foto.'], 500);
            }

            // Simpan gambar
            file_put_contents($folderPath . '/' . $filename, $imageData);

            // Cek apakah sudah ada absensi untuk hari ini
            $userId = auth()->id();
            $today = now()->toDateString(); // Mendapatkan tanggal hari ini (format Y-m-d)

            // Cek apakah sudah ada absensi untuk hari ini
            $absensi = Absensi::where('user_id', $userId)
                ->whereDate('jam_masuk', $today)
                ->first();

            if ($absensi) {
                // Jika sudah ada absensi, kita update jam_pulang
                $absensi->jam_pulang = now();  // Update jam_pulang
                $absensi->foto = 'uploads/' . $filename; // Ganti foto absensi pulang
                $absensi->titik_lokasi = $validated['titik_lokasi'];
                $absensi->save();

                // Log sukses
                Log::info('Absensi pulang berhasil disimpan:', $absensi->toArray());

                return response()->json(['message' => 'Absensi pulang berhasil disimpan!', 'data' => $absensi], 201);
            } else {
                // Jika belum ada absensi, simpan absensi pertama (jam masuk)
                $absensi = Absensi::create([
                    'user_id' => $userId,
                    'foto' => 'uploads/' . $filename,
                    'jam_masuk' => now(),
                    'titik_lokasi' => $validated['titik_lokasi'],
                    'jam_pulang' => null,  // Absensi masuk, jam_pulang kosong
                ]);

                // Log sukses
                Log::info('Absensi masuk berhasil disimpan:', $absensi->toArray());

                return response()->json(['message' => 'Absensi masuk berhasil disimpan!', 'data' => $absensi], 201);
            }
        } catch (\Exception $e) {
            // Log error
            Log::error('Error saat menyimpan absensi:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Gagal menyimpan absensi.'], 500);
        }
    }

    public function store222(Request $request)
    {
        try {
            // Log data yang diterima
            Log::info('Data diterima dari frontend:', $request->all());

            // Validasi data
            $validated = $request->validate([
                'foto' => 'required|string',
                'titik_lokasi' => 'required|string',
            ]);

            // Proses simpan file foto
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->foto));

            // Debug: Log panjang image data
            Log::info('Panjang data gambar setelah decode: ', ['length' => strlen($imageData)]);

            $filename = time() . '.png';
            $folderPath = public_path('uploads');

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            // Pastikan folder bisa ditulis
            if (!is_writable($folderPath)) {
                Log::error('Folder uploads tidak dapat ditulis:', ['folder' => $folderPath]);
                return response()->json(['message' => 'Server tidak dapat menyimpan file foto.'], 500);
            }

            // Simpan gambar
            file_put_contents($folderPath . '/' . $filename, $imageData);

            // Cek apakah sudah ada absensi untuk hari ini
            $userId = auth()->id();
            $today = now()->toDateString(); // Mendapatkan tanggal hari ini (format Y-m-d)

            // Cek apakah sudah ada absensi untuk hari ini
            $absensi = Absensi::where('user_id', $userId)
                ->whereDate('jam_masuk', $today)
                ->first();

            if ($absensi) {
                // Jika sudah ada absensi, kita update jam_pulang
                $absensi->jam_pulang = now();  // Update jam_pulang
                $absensi->foto = 'uploads/' . $filename; // Ganti foto absensi pulang
                $absensi->titik_lokasi = $validated['titik_lokasi'];
                $absensi->save();

                // Format jam_pulang ke format HH:MM:SS
                $formattedJamPulang = $absensi->jam_pulang->format('H:i:s');

                // Log sukses
                Log::info('Absensi pulang berhasil disimpan:', $absensi->toArray());

                return response()->json([
                    'message' => 'Absensi pulang berhasil disimpan!',
                    'jam_pulang' => $formattedJamPulang,  // Mengirimkan data jam_pulang yang sudah diformat
                    'data' => $absensi
                ], 201);
            } else {
                // Jika belum ada absensi, simpan absensi pertama (jam masuk)
                $absensi = Absensi::create([
                    'user_id' => $userId,
                    'foto' => 'uploads/' . $filename,
                    'jam_masuk' => now(),
                    'titik_lokasi' => $validated['titik_lokasi'],
                    'jam_pulang' => null,  // Absensi masuk, jam_pulang kosong
                ]);

                // Log sukses
                Log::info('Absensi masuk berhasil disimpan:', $absensi->toArray());

                return response()->json([
                    'message' => 'Absensi masuk berhasil disimpan!',
                    'data' => $absensi
                ], 201);
            }
        } catch (\Exception $e) {
            // Log error
            Log::error('Error saat menyimpan absensi:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Gagal menyimpan absensi.'], 500);
        }
    }


    public function store2223(Request $request)
    {
        try {
            // Log data yang diterima
            Log::info('Data diterima dari frontend:', $request->all());

            // Validasi data
            $validated = $request->validate([
                'foto' => 'required|string',
                'titik_lokasi' => 'required|string',
            ]);

            // Proses simpan file foto
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->foto));

            // Debug: Log panjang image data
            Log::info('Panjang data gambar setelah decode: ', ['length' => strlen($imageData)]);

            $filename = time() . '.png';
            $folderPath = public_path('uploads');

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            // Pastikan folder bisa ditulis
            if (!is_writable($folderPath)) {
                Log::error('Folder uploads tidak dapat ditulis:', ['folder' => $folderPath]);
                return response()->json(['message' => 'Server tidak dapat menyimpan file foto.'], 500);
            }

            // Simpan gambar
            file_put_contents($folderPath . '/' . $filename, $imageData);

            // Cek apakah sudah ada absensi untuk hari ini
            $userId = auth()->id();
            $today = now()->toDateString(); // Mendapatkan tanggal hari ini (format Y-m-d)

            // Cek apakah sudah ada absensi masuk hari ini
            $absensiMasuk = Absensi::where('user_id', $userId)
                ->whereDate('jam_masuk', $today)
                ->first();

            // Cek apakah sudah ada absensi pulang hari ini
            $absensiPulang = Absensi::where('user_id', $userId)
                ->whereDate('jam_masuk', $today)
                ->whereNotNull('jam_pulang')
                ->first();

            if ($absensiMasuk && !$absensiPulang) {
                // Jika sudah ada absensi masuk, kita update jam_pulang
                $absensiMasuk->jam_pulang = now();  // Update jam_pulang
                $absensiMasuk->foto = 'uploads/' . $filename; // Ganti foto absensi pulang
                $absensiMasuk->titik_lokasi = $validated['titik_lokasi'];
                $absensiMasuk->save();

                // Format jam_pulang ke format HH:MM:SS
                $formattedJamPulang = $absensiMasuk->jam_pulang->format('H:i:s');

                // Log sukses
                Log::info('Absensi pulang berhasil disimpan:', $absensiMasuk->toArray());

                return response()->json([
                    'message' => 'Absensi pulang berhasil disimpan!',
                    'jam_pulang' => $formattedJamPulang,  // Mengirimkan data jam_pulang yang sudah diformat
                    'data' => $absensiMasuk
                ], 201);
            } elseif (!$absensiMasuk && !$absensiPulang) {

                $absensi = Absensi::create([
                    'user_id' => $userId,
                    'foto' => 'uploads/' . $filename,
                    'jam_masuk' => now(),
                    'titik_lokasi' => $validated['titik_lokasi'],
                    'jam_pulang' => null,  // Absensi masuk, jam_pulang kosong
                ]);

                // Log sukses
                Log::info('Absensi masuk berhasil disimpan:', $absensi->toArray());

                return response()->json([
                    'message' => 'Absensi masuk berhasil disimpan!',
                    'data' => $absensi
                ], 201);
            } else {
                // Jika sudah ada absensi masuk dan pulang pada hari yang sama
                return response()->json([
                    'message' => 'Kamu sudah absen hari ini.',
                ], 400); // Status code 400 untuk kesalahan pada permintaan
            }
        } catch (\Exception $e) {
            // Log error
            Log::error('Error saat menyimpan absensi:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Kamu sudah absen hari ini.'], 500);
        }
    }

    public function store_dengan_emot(Request $request)
    {
        try {
            Log::info('Data diterima dari frontend:', $request->all());

            $validated = $request->validate([
                'foto' => 'required|string',
                'titik_lokasi' => 'required|string',
            ]);

            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->foto));
            $filename = time() . '.png';
            $folderPath = public_path('uploads');

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            file_put_contents($folderPath . '/' . $filename, $imageData);

            $userId = auth()->id();
            $userName = auth()->user()->name; // Pastikan field 'name' ada di tabel pengguna
            $today = now()->toDateString();

            $absensiMasuk = Absensi::where('user_id', $userId)
                ->whereDate('jam_masuk', $today)
                ->first();

            $absensiPulang = Absensi::where('user_id', $userId)
                ->whereDate('jam_masuk', $today)
                ->whereNotNull('jam_pulang')
                ->first();

            $chatId = '5985430823';

            if ($absensiMasuk && !$absensiPulang) {
                $absensiMasuk->jam_pulang = now();
                $absensiMasuk->foto = 'uploads/' . $filename;
                $absensiMasuk->titik_lokasi = $validated['titik_lokasi'];
                $absensiMasuk->save();

                $formattedJamPulang = $absensiMasuk->jam_pulang->format('H:i:s');
                $message = "👤 Nama : {$userName}\n📍 Titik Lokasi : {$validated['titik_lokasi']}\n🔚 Jam Pulang : {$formattedJamPulang}";
                self::sendMessage($chatId, $message);

                Log::info('Absensi pulang berhasil disimpan:', $absensiMasuk->toArray());

                return response()->json([
                    'message' => 'Absensi pulang berhasil disimpan!',
                    'jam_pulang' => $formattedJamPulang,
                    'data' => $absensiMasuk
                ], 201);
            } elseif (!$absensiMasuk && !$absensiPulang) {
                $absensi = Absensi::create([
                    'user_id' => $userId,
                    'foto' => 'uploads/' . $filename,
                    'jam_masuk' => now(),
                    'titik_lokasi' => $validated['titik_lokasi'],
                    'jam_pulang' => null,
                ]);

                $formattedJamMasuk = $absensi->jam_masuk->format('H:i:s');
                $message = "👤 Nama: {$userName}\n📍 Titik Lokasi: {$validated['titik_lokasi']}\n🔛 Jam Masuk : {$formattedJamMasuk}";
                self::sendMessage($chatId, $message);

                Log::info('Absensi masuk berhasil disimpan:', $absensi->toArray());

                return response()->json([
                    'message' => 'Absensi masuk berhasil disimpan!',
                    'data' => $absensi
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Kamu sudah absen hari ini.',
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan absensi:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan.'], 500);
        }
    }


    public function store(Request $request)
    {
        try {
            // Log data yang diterima
            Log::info('Data diterima dari frontend:', $request->all());

            // Validasi data
            $validated = $request->validate([
                'foto' => 'required|string',
                'titik_lokasi' => 'required|string',
            ]);

            // Proses simpan file foto
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $request->foto));
            $filename = time() . '.png';
            $folderPath = public_path('uploads');

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            if (!is_writable($folderPath)) {
                Log::error('Folder uploads tidak dapat ditulis:', ['folder' => $folderPath]);
                return response()->json(['message' => 'Server tidak dapat menyimpan file foto.'], 500);
            }

            file_put_contents($folderPath . '/' . $filename, $imageData);

            // Cek apakah sudah ada absensi untuk hari ini
            $userId = auth()->id();
            $today = now()->toDateString();

            $absensiMasuk = Absensi::where('user_id', $userId)
                ->whereDate('jam_masuk', $today)
                ->first();

            $absensiPulang = Absensi::where('user_id', $userId)
                ->whereDate('jam_masuk', $today)
                ->whereNotNull('jam_pulang')
                ->first();

            $chatId = '5985430823';
            $botToken = '7085351448:AAErPRbIkJJOwkDTIMFUlwNU3AN_UQ1cRkY';

            if ($absensiMasuk && !$absensiPulang) {
                // Update jam_pulang
                $absensiMasuk->jam_pulang = now();
                $absensiMasuk->foto = 'uploads/' . $filename;
                $absensiMasuk->titik_lokasi = $validated['titik_lokasi'];
                $absensiMasuk->save();

                $formattedJamPulang = $absensiMasuk->jam_pulang->format('H:i:s');

                // Kirim notifikasi ke Telegram
                $message = "Absensi Pulang 🏠:\n" .
                    "🧑 Nama: " . auth()->user()->name . "\n" .
                    "📍 Titik Lokasi: {$validated['titik_lokasi']}\n" .
                    "🏠 Jam Pulang: {$formattedJamPulang}";
                self::sendMessage($chatId, $message, $botToken);

                Log::info('Absensi pulang berhasil disimpan:', $absensiMasuk->toArray());

                return response()->json([
                    'message' => 'Absensi pulang berhasil disimpan!',
                    'jam_pulang' => $formattedJamPulang,
                    'data' => $absensiMasuk
                ], 201);
            } elseif (!$absensiMasuk && !$absensiPulang) {
                // Buat absensi baru
                $absensi = Absensi::create([
                    'user_id' => $userId,
                    'foto' => 'uploads/' . $filename,
                    'jam_masuk' => now(),
                    'titik_lokasi' => $validated['titik_lokasi'],
                    'jam_pulang' => null,
                ]);

                // Kirim notifikasi ke Telegram
                $message = "Absensi Masuk 🏢:\n" .
                    "🧑 Nama: " . auth()->user()->name . "\n" .
                    "📍 Titik Lokasi: {$validated['titik_lokasi']}\n" .
                    "🏢 Jam Masuk: " . $absensi->jam_masuk->format('H:i:s');
                self::sendMessage($chatId, $message, $botToken);

                Log::info('Absensi masuk berhasil disimpan:', $absensi->toArray());

                return response()->json([
                    'message' => 'Absensi masuk berhasil disimpan!',
                    'data' => $absensi
                ], 201);
            } else {
                // Sudah ada absensi masuk dan pulang pada hari yang sama
                return response()->json([
                    'message' => 'Kamu sudah absen hari ini.',
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan absensi:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan.'], 500);
        }
    }


    public function updatePulang(Request $request)
    {
        $user = auth()->user(); // Ambil user yang login
        $absensi = Absensi::where('user_id', $user->id)
            ->whereNotNull('jam_masuk')
            ->whereNull('jam_pulang')
            ->first();

        if (!$absensi) {
            return response()->json(['message' => 'Tidak ada absensi yang dapat diperbarui!'], 404);
        }

        $absensi->update(['jam_pulang' => now()]);

        return response()->json(['message' => 'Absensi pulang berhasil!', 'data' => $absensi]);
    }





    public function getAbsensiData2()
    {
        try {
            // Mengambil data absensi yang sudah lengkap
            $absensi = Absensi::with('user') // Mengambil data absensi beserta data user
                ->orderBy('created_at', 'desc')
                ->get();

            // Menyiapkan data untuk dikirim ke frontend
            $data = $absensi->map(function ($item) {
                return [
                    'id' => $item->id,
                    'user_name' => $item->user->name, // Mengambil nama user
                    'jam_masuk' => $item->jam_masuk, // Format jam_masuk
                    'jam_pulang' => $item->jam_pulang,
                    'titik_lokasi' => $item->titik_lokasi,
                    'foto' => asset($item->foto), // Menyediakan URL foto
                ];
            });

            // Mengirim data absensi dalam format JSON
            return response()->json([
                'data' => $data
            ]);
        } catch (\Exception $e) {
            // Log error
            Log::error('Error saat mengambil data absensi:', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Gagal mengambil data absensi.'], 500);
        }
    }

    public function getAbsensiData()
    {
        $absensi = Absensi::all();
        return view('absensi.dashboard', compact('absensi'));
    }

    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $absensi->delete();

        return redirect()->route('absensi.dashboard')->with('success', 'Absensi berhasil dihapus.');
    }



    public static function sendMessage($chatId, $message)
    {
        try {
            $botToken = '7085351448:AAErPRbIkJJOwkDTIMFUlwNU3AN_UQ1cRkY';
            $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

            $response = Http::post($url, [
                'chat_id' => $chatId,
                'text' => $message,
            ]);

            if ($response->failed()) {
                Log::error('Gagal mengirim pesan Telegram:', $response->json());
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Error saat mengirim pesan Telegram:', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
