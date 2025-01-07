@extends('superadmin.layout_superadmin')

@section('konten')
    <div class="container mt-4">
        <h3 class="text text-center text-black mt-3"> Edit Data Pembayaran : {{ $pembayaran->nama_plg }} </h3>
        <form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST">
            @csrf
            @method('PUT')

            <label for="paket_plg">Paket Pelanggan</label>
            <input type="text" name="paket_plg" value="{{ old('paket_plg', $pembayaran->paket_plg) }}"
                class="form-control mt-2">

            <label for="jumlah_pembayaran">Jumlah Pembayaran</label>
            <input type="text" name="jumlah_pembayaran"
             value="{{ old('jumlah_pembayaran', $pembayaran->jumlah_pembayaran) }}" class="form-control mt-2">

            <label for="metode_transaksi">Metode Transaksi</label>
            <input type="text" name="metode_transaksi"
            value="{{ old('metode_transaksi', $pembayaran->metode_transaksi) }}" class="form-control mt-2">

            <label for="keterangan_plg">Keterangan Pelanggan</label>
            <input type="text" name="keterangan_plg" value="{{ old('keterangan_plg', $pembayaran->keterangan_plg) }}"
            class="form-control mt-2">

            <label for="created_at">Tanggal</label>
            <input type="datetime-local" name="created_at"
            value="{{ old('created_at', $pembayaran->created_at->format('Y-m-d\TH:i')) }}" class="form-control mt-2">

            <label for="tanggal_pembayaran">Bayar Untuk Bulan</label>
            <input type="month" name="tanggal_pembayaran"

            value="{{ old('tanggal_pembayaran', \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('Y-m')) }}" class="form-control mt-2">




            <button class="btn btn-primary btn-sm mt-4">Simpan</button>
        </form>
    </div>
@endsection
