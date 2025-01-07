@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Halaman Pembayaran</h1>

        <!-- Informasi Pelanggan -->
        <div class="card">
            <div class="card-body">
                <h4>Nama: {{ $pelanggan->nama_plg }}</h4>
                <p>Alamat: {{ $pelanggan->alamat_plg }}</p>
                <p>Paket: {{ $pelanggan->paket_plg }}</p>
                <p>Harga Paket: {{ number_format($pelanggan->harga_paket, 0, ',', '.') }}</p>
                <p>Bulan Terakhir Pembayaran: {{ $lastMonth }}</p>
            </div>
        </div>

        <!-- Form Proses Pembayaran -->
        <form action="{{ route('pelanggan.processPayment', $pelanggan->id) }}" method="POST" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-primary btn-lg">Bayar</button>
        </form>
    </div>
@endsection
