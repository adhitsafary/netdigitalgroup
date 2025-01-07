<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BroadcastController;
use App\Http\Controllers\CobaController;
use App\Http\Controllers\IsolirController;
use App\Http\Controllers\JumlahLainLainController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KasbonController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PelangganBayarSendiriController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\PelangganOfController;
use App\Http\Controllers\PemasukanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengeluaranController;
use App\Http\Controllers\PerbaikanController;
use App\Http\Controllers\RekapMutasiHarianController;
use App\Http\Controllers\RekapPemasanganController;
use App\Http\Controllers\RekapPemasanganControlller;
use App\Http\Controllers\SesiController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\UserController;
use App\Models\Pelanggan;
use App\Models\Pelangganof;
use Illuminate\Support\Facades\Route;



//PERBAIKAN
Route::get('/home', [PelangganController::class, 'home'])->name('index');
//Route::get('/login', [PerbaikanController::class, 'login'])->name('auth.login');

//unutk teknisi
Route::get('/perbaikan', [PerbaikanController::class, 'index'])->name('perbaikan.index');
//untukadmin
Route::get('/psb/create', [PerbaikanController::class, 'create_psb'])->name('psb.create');
Route::get('/perbaikan/create', [PerbaikanController::class, 'create'])->name('perbaikan.create');
Route::post('/psb/store', [PerbaikanController::class, 'store_psb'])->name('perbaikan.store');
Route::post('/perbaikan/store', [PerbaikanController::class, 'store'])->name('psb.store');
Route::get('/perbaikan/edit/{id}', [PerbaikanController::class, 'edit'])->name('perbaikan.edit');
Route::post('/perbaikan/update/{id}', [PerbaikanController::class, 'update'])->name('perbaikan.update');
Route::post('/perbaikan/hapus/{id}', [PerbaikanController::class, 'destroy'])->name('perbaikan.destroy');

Route::get('/perbaikan/export-pdf', [PerbaikanController::class, 'exportPdf'])->name('perbaikan.exportPdf');
Route::get('/perbaikan/export-excel', [PerbaikanController::class, 'exportExcel'])->name('perbaikan.exportExcel');

//coba
//Route::get('/pelanggancoba', [PelangganController::class, 'indexcoba'])->name('pelanggan.index');
//PELANGGAN
Route::get('/pelanggan', [PelangganController::class, 'index'])->name('pelanggan.index');
Route::get('/pelanggan/create', [PelangganController::class, 'create'])->name('pelanggan.create');
Route::post('/pelanggan/store', [PelangganController::class, 'store'])->name('pelanggan.store');
Route::get('/pelanggan/edit/{id}', [PelangganController::class, 'edit'])->name('pelanggan.edit');
Route::post('/pelanggan/update/{id}', [PelangganController::class, 'update'])->name('pelanggan.update');
Route::post('/pelanggan/hapus/{id}', [PelangganController::class, 'destroy'])->name('pelanggan.destroy');

//Detail
Route::get('/pelanggan/{id}/detail', [PelangganController::class, 'detail'])->name('pelanggan.detail');
Route::get('/pelanggan/off/{id}', [PelangganController::class, 'pelanggan_off'])->name('pelanggan.off');

//Pelangan Off
Route::get('/pelangganof', [PelangganOfController::class, 'index'])->name('pelangganof.index');
Route::get('/pelangganof/edit/{id}', [PelangganOfController::class, 'edit'])->name('pelangganof.edit');
Route::post('/pelangganof/update/{id}', [PelangganOfController::class, 'update'])->name('pelangganof.update');
Route::delete('/pelangganof/delete/{id}', [PelangganOfController::class, 'destroy'])->name('pelangganof.destroy');
Route::get('/pelangganof/{id}/detail', [PelangganOfController::class, 'detail'])->name('pelangganof.detail');
Route::get('/pelanggan/aktifkan/{id}', [PelangganOfController::class, 'showOff'])->name('aktifkan_pelanggan');


Route::post('/pelanggan/{id}/pembayaran', [PelangganController::class, 'pembayaran'])->name('pelanggan.pembayaran');
Route::get('/pembayaran/edit/{id}', [PembayaranController::class, 'edit'])->name('pembayaran.edit');
Route::put('/pembayaran/update/{id}', [PembayaranController::class, 'update'])->name('pembayaran.update');



Route::patch('/pelanggan/{id}/toggle-visibility', [PelangganController::class, 'toggleVisibility'])->name('pelanggan.toggleVisibility');
Route::get('/pelanggan/{id}/history', [PelangganController::class, 'history'])->name('pelanggan.history');

//===========ini untuk pembayaran pelanggan sendiri sendiri =====================
Route::post('isolir/{id}/reactivate', [IsolirController::class, 'reactivatePelanggan'])->name('isolir.bayar');

Route::post('pelanggan/{id}/bayar', [PelangganController::class, 'bayar'])->name('pelanggan.bayar');
//Route::post('isolir/{id}/bayar', [IsolirController::class, 'bayar'])->name('isolir.bayar');
Route::get('/pelanggan/{id}/historypembayaran', [PelangganController::class, 'historypembayaran'])->name('pelanggan.historypembayaran');
Route::get('/isolir/{id}/historypembayaran', [IsolirController::class, 'historypembayaran'])->name('isolir.historypembayaran');


//index pembayaran semua user  atau global
Route::get('/bayar-pelanggan/export/{format}', [PembayaranController::class, 'export'])->name('pembayaran.export');
Route::post('/pembayaran/hapus/{id}', [PembayaranController::class, 'destroy'])->name('pembayaran.destroy');

Route::get('/broadcast', [BroadcastController::class, 'index'])->name('broadcast.index');
Route::post('/broadcast/send', [BroadcastController::class, 'send'])->name('broadcast.send');

//Whatsapp Brodcast
Route::get('/send-message', [MessageController::class, 'create'])->name('whatsapp.send-message');
Route::post('/send-message', [MessageController::class, 'store'])->name('message.store');

//PEMBAYARAN GLOBAL
Route::get('/pembayaran', [PembayaranController::class, 'index'])->name('pembayaran.index');
//chart bulanan
Route::get('/dashboard', [PelangganController::class, 'getMonthlyPayments']);

//midleware
Route::middleware(['guest'])->group(function () {
    //SesiLogin
    Route::get('/', [SesiController::class, 'index'])->name('login');
    Route::post('/', [SesiController::class, 'login']);
});

Route::get('/home', function () {
    return redirect('/masuk/admin');
});
Route::get('/teknisi/baru', [TeknisiController::class, 'index'])->name('teknisi');
Route::get('/homebaru', [PelangganController::class, 'home'])->name('index');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        // Cek role user
        if (auth()->user()->role == 'teknisi') {
            return redirect()->route('teknisi.index'); // Redirect ke halaman teknisi
        } elseif (auth()->user()->role == 'admin') {
            return redirect('/masuk/admin'); // Redirect ke halaman admin
        } elseif (auth()->user()->role == 'superadmin') {
            return redirect('/masuk/superadmin'); // Redirect ke halaman superadmin
        }

        // Default return jika tidak ada role yang cocok
        return abort(403, 'Unauthorized access.');
    });

    // Rute teknisi
    Route::get('/masuk/teknisi', [TeknisiController::class, 'index'])
        ->middleware('userAkses:teknisi,superadmin') // Superadmin bisa akses teknisi
        ->name('teknisi.index');

    // Rute admin
    Route::get('/masuk/admin', [PelangganController::class, 'home'])
        ->middleware('userAkses:admin,superadmin'); // Superadmin bisa akses admin

    // Rute superadmin
    Route::get('/masuk/superadmin', [SuperAdminController::class, 'home'])
        ->middleware('userAkses:superadmin');

    // Logout
    Route::get('/logout', [SesiController::class, 'logout'])->name('logout');
});



Route::get('coba', [TeknisiController::class, 'coba']);

Route::get('/pelanggan/belum_bayar', [PelangganController::class, 'belumBayar'])->name('pelanggan.belum_bayar');


Route::get('/cekdulu', [CobaController::class, 'create']);
//landing page
Route::get('/home2', [PerbaikanController::class, 'home2']);

Route::get('/search-pelanggan', [PerbaikanController::class, 'searchPelanggan'])->name('pelanggan.search');
Route::get('/get-pelanggan/{id}', [PerbaikanController::class, 'getPelanggan'])->name('pelanggan.get');



Route::get('/rekap-teknisi', [PerbaikanController::class, 'rekapTeknisi'])->name('perbaikan.rekapTeknisi');

Route::get('/teknisi/rekap-teknisi', [TeknisiController::class, 'rekapTeknisi'])->name('teknisi.rekap_teknisi');
Route::post('/rekap-teknisi/print', [PerbaikanController::class, 'printRekapTeknisi'])->name('perbaikan.printRekapTeknisi');

Route::post('/perbaikan/{id}/selesai', [PerbaikanController::class, 'selesai'])->name('perbaikan.selesai');

//Alamat Karyawan
Route::get('/masuk/superadmin/karyawan', [KaryawanController::class, 'home'])->name('karyawan.index');
Route::get('/masuk/superadmin/karyawan/create', [KaryawanController::class, 'create'])->name('karyawan.create');
Route::post('/masuk/superadmin/karyawan/store', [KaryawanController::class, 'store'])->name('karyawan.store');
Route::get('/masuk/superadmin/karyawan/edit/{id}', [KaryawanController::class, 'edit'])->name('karyawan.edit');
Route::post('/masuk/superadmin/karyawan/update/{id}', [KaryawanController::class, 'update'])->name('karyawan.update');
Route::delete('/masuk/superadmin/karyawan/delete/{id}', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
Route::get('/masuk/superadmin/karyawan/{id}/detail', [KaryawanController::class, 'detail'])->name('karyawan.detail');
Route::get('/masuk/superadmin/karyawan/aktifkan/{id}', [KaryawanController::class, 'showOff'])->name('karyawan.non_aktifkan');

//Alamat Kasbon
Route::get('/masuk/superadmin/karyawan/kasbon', [KasbonController::class, 'index'])->name('kasbon.index');
Route::get('/masuk/superadmin/karyawan/{id}/kasbon/create', [KasbonController::class, 'create'])->name('kasbon.create');
Route::post('/masuk/superadmin/karyawan/kasbon/store', [KasbonController::class, 'store'])->name('kasbon.store');
Route::get('/masuk/superadmin/karyawan/kasbon/edit/{id}', [KasbonController::class, 'edit'])->name('kasbon.edit');
Route::post('/masuk/superadmin/karyawan/kasbon/update/{id}', [KasbonController::class, 'update'])->name('kasbon.update');
Route::delete('/masuk/superadmin/karyawan/kasbon/delete/{id}', [KasbonController::class, 'destroy'])->name('kasbon.destroy');

//pengeluaran lainya
Route::get('/pengeluaran', [PengeluaranController::class, 'index'])->name('pengeluaran.index');
Route::get('/pengeluaran/create', [PengeluaranController::class, 'create'])->name('pengeluaran.create');
Route::post('/pengeluaran/store', [PengeluaranController::class, 'store'])->name('pengeluaran.store');
Route::get('/pengeluaran/edit/{id}', [PengeluaranController::class, 'edit'])->name('pengeluaran.edit');
Route::post('/pengeluaran/update/{id}', [PengeluaranController::class, 'update'])->name('pengeluaran.update');
Route::post('/pengeluaran/hapus/{id}', [PengeluaranController::class, 'destroy'])->name('pengeluaran.destroy');

//Rekap Pemasangan
Route::get('/rekap_pemasangan', [RekapPemasanganController::class, 'index'])->name('rekap_pemasangan.index');
Route::get('/rekap_pemasangan/create', [RekapPemasanganController::class, 'create'])->name('rekap_pemasangan.create');
Route::post('/rekap_pemasangan/store', [RekapPemasanganController::class, 'store'])->name('rekap_pemasangan.store');
Route::get('/rekap_pemasangan/edit/{id}', [RekapPemasanganController::class, 'edit'])->name('rekap_pemasangan.edit');
Route::post('/rekap_pemasangan/update/{id}', [RekapPemasanganController::class, 'update'])->name('rekap_pemasangan.update');
Route::post('/rekap_pemasangan/hapus/{id}', [RekapPemasanganController::class, 'destroy'])->name('rekap_pemasangan.destroy');

//pemasukan lainya
Route::get('/pemasukan', [PemasukanController::class, 'index'])->name('pemasukan.index');
Route::get('/pemasukan/create', [PemasukanController::class, 'create'])->name('pemasukan.create');
Route::post('/pemasukan/store', [PemasukanController::class, 'store'])->name('pemasukan.store');
Route::get('/pemasukan/edit/{id}', [PemasukanController::class, 'edit'])->name('pemasukan.edit');
Route::post('/pemasukan/update/{id}', [PemasukanController::class, 'update'])->name('pemasukan.update');
Route::post('/pemasukan/hapus/{id}', [PemasukanController::class, 'destroy'])->name('pemasukan.destroy');

// Routes untuk isolir

//Isolir
Route::get('/isolir', [IsolirController::class, 'index'])->name('isolir.index');
Route::get('/isolir/edit/{id}', [IsolirController::class, 'edit'])->name('isolir.edit');
Route::post('/isolir/update/{id}', [IsolirController::class, 'update'])->name('isolir.update');
Route::delete('/isolir/delete/{id}', [IsolirController::class, 'destroy'])->name('isolir.destroy');
Route::get('/isolir/{id}/detail', [IsolirController::class, 'detail'])->name('isolir.detail');
Route::get('/isolir/aktifkan/{id}', [IsolirController::class, 'showOff'])->name('isolir.aktifkan_pelanggan');

// web.php
//Route::post('/isolir/reactivate/{id}', [IsolirController::class, 'reactivatePelanggan'])->name('pelanggan.reactivate');




Route::post('/isolir/{id}/activate', [IsolirController::class, 'activate'])->name('isolir.activate');
Route::get('/isolir/cleanup', [IsolirController::class, 'cleanUp'])->name('isolir.cleanup');

// web.php
Route::post('pelanggan/to-isolir/{id}', [PelangganController::class, 'toIsolir'])->name('pelanggan.toIsolir');
// web.php
Route::post('pelanggan/to-off/{id}', [IsolirController::class, 'toOff'])->name('pelanggan.toOff');




//rekap mutasi harian
Route::get('/rekap-mutasi-harian', [RekapMutasiHarianController::class, 'index'])->name('rekap.mutasi.harian');


Route::post('pelanggan/{id}/update-status', [PelangganController::class, 'updateStatus'])->name('pelanggan.updateStatus');

Route::get('/rekap-harian', [JumlahLainLainController::class, 'lihatRekapHarian'])->name('rekap-harian');

//filter pelanggan harian tgl_tagih_plg
Route::get('/pelanggan/tagihan', [PelangganController::class, 'filterByTanggalTagih'])->name('pelanggan.filterTagih');
//filter di index  pelanggan
Route::get('/pelanggan/filter-tagih', [PelangganController::class, 'filterByTanggalTagihindex'])->name('pelanggan.filterTagihindex');

Route::get('/pelanggan/tagihan/index', [PelangganController::class, 'filterByTanggalTagihindex'])->name('pelanggan.filterTagihindex');
//filter di pembayaran
Route::get('/pembayaran/filter/', [PembayaranController::class, 'index'])->name('pembayaran.filter');
//filter di isolir
Route::get('/isolir/tagihan/index', [IsolirController::class, 'filterByTanggalTagihindex'])->name('isolir.filterTagihindex');
//filter pelanggan Off
Route::get('/pelanggan/tagihan/index', [PelangganOfController::class, 'filterByTanggalTagihindex'])->name('pelangganof.filterTagihindex');
//isolir asli
Route::get('/check-isolir', [PelangganController::class, 'checkAndMoveToIsolir'])->name('check.isolir');
//cek payment asli
Route::get('/update-payment-status', [PelangganController::class, 'updatePaymentStatus'])->name('update.payment.status');
Route::get('/update-payment-isolir', [PelangganController::class, 'updatePaymentStatusIsolir'])->name('update.payment.isolir');
//reactive bayar
Route::post('/reactivate-bayar', [IsolirController::class, 'reactivateAndBayar'])->name('pelanggan.reactivateAndBayar');

//pelanggan bayar
Route::get('/pembayaran/csbayar', [PelangganBayarSendiriController::class, 'index'])->name('pembayaran.csbayar');
Route::get('/costumer', [PelangganBayarSendiriController::class, 'index'])->name('costumer.index');


// Route untuk admin
// Route::middleware(['role:admin'])->group(function () {
//   Route::get('/masuk/admin', [AdminController::class, 'index']);
//   Route::get('/masuk/admin/karyawan', [AdminController::class, 'karyawan']);
// });

// Route untuk superadmin dengan akses hanya ke sub-route tertentu
// Route::middleware(['role:superadmin,masuk/superadmin/karyawan'])->group(function () {
//  Route::get('/masuk/superadmin/karyawan', [SuperAdminController::class, 'karyawan']);
// });

// Route untuk teknisi
// Route::middleware(['role:teknisi'])->group(function () {
//   Route::get('/masuk/teknisi', [TeknisiController::class, 'index']);
//});

Route::get('/pindahroute', function () {
    return view('pindahroute');
});

Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');
