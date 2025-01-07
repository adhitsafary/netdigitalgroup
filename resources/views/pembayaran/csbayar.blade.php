@extends('layout2')

@section('konten')
    <div class="p-5">
        <div class="pl-5 pr-5 mb-4">
            <div class="row mb-2 align-items-center">
                <div class="col-md-6" style="color: black">
                    <form action="{{ route('pembayaran.csbayar') }}" method="GET" class="form-inline">
                        <div class="input-group">
                            <input type="text" name="search" id="search" class="form-control font-weight-bold"
                                value="{{ request('search') }}" placeholder="Cari berdasarkan ID Pelanggan">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary ml-2">Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @if(!empty($pelanggan) && count($pelanggan) > 0)
                <table class="table table-bordered table-responsive" style="color: black;">
                    <thead class="table table-primary">
                        <tr class="font-weight-bold">
                            <th>ID Pelanggan</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>No Telepon</th>
                            <th>Aktivasi</th>
                            <th>Harga Paket</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pelanggan as $item)
                            <tr>
                                <td>{{ $item->id_plg }}</td>
                                <td>{{ $item->nama_plg }}</td>
                                <td>{{ $item->alamat_plg }}</td>
                                <td>{{ $item->no_telepon_plg }}</td>
                                <td>{{ $item->aktivasi_plg }}</td>
                                <td>{{ number_format($item->harga_paket, 0, ',', '.') }}</td>
                                <td><a href="{{ route('pelanggan.detail', $item->id_plg) }}" class="btn btn-warning btn-sm">Detail</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-info">
                    @if(request('search'))
                        Tidak ada pelanggan dengan ID {{ request('search') }} ditemukan.
                    @else
                        Silakan masukkan ID pelanggan untuk melakukan pencarian.
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection
