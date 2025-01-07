@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="mb-4">Bayar Tagihan</h1>
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('automatispayment.index') }}" method="GET">
            <div class="form-group">
                <input type="text" name="q" class="form-control" placeholder="Cari berdasarkan ID atau Nama"
                    value="{{ $query ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary mt-2">Cari</button>
        </form>

        <div class="mt-4">
            @if (!$query)
                <p class="text-muted">Silakan masukkan ID atau Nama untuk mencari data pelanggan.</p>
            @elseif($pelanggan->isEmpty())
                <p class="text-muted">Tidak ditemukan hasil untuk "{{ $query }}"</p>
            @else
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>ID Pelanggan</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th>Aktivasi</th>
                            <th>Paket</th>
                            <th>Harga</th>
                            <th>Tanggal Tagih</th>
                            <th>Keterangan</th>
                            <th>Status Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pelanggan as $no => $item)
                            <tr>
                                <td>{{ ($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration }}</td>
                                <td>{{ $item->id_plg }}</td>
                                <td>{{ $item->nama_plg }}</td>
                                <td>{{ $item->alamat_plg }}</td>
                                <td>{{ $item->no_telepon_plg }}</td>
                                <td>{{ $item->aktivasi_plg }}</td>
                                <td>{{ $item->paket_plg }}</td>
                                <td>{{ number_format($item->harga_paket, 0, ',', '.') }}</td>
                                <td>{{ $item->tgl_tagih_plg }}</td>
                                <td>{{ $item->keterangan_plg }}</td>
                                <td>
                                    {{ optional($item->pembayaran->last())->tanggal_pembayaran ?? 'Belum Bayar' }}
                                </td>
                                <td>
                                    <a href="{{ route('pelanggan.payment', $item->id) }}"
                                        class="btn btn-success btn-sm">Bayar</a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{ $pelanggan->links() }}
            @endif
        </div>
    </div>
@endsection
