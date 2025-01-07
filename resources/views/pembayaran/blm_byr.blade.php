@extends($layout)

@section('konten')
    <div class="container mt-4">
        <h4 class="mb-4">Daftar Pelanggan yang Belum Membayar Bulan Ini</h4>

        <!-- Form Pencarian -->
        <form action="{{ route('pembayaran.index') }}" method="GET" class="form-inline mb-4">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Cari berdasarkan ID atau Nama">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>

        <table class="table table-bordered table-responsive">
            <thead class="table table-primary">
                <tr>
                    <th>ID</th>
                    <th>Nama Pelanggan</th>
                    <th>Alamat</th>
                    <th>Tanggal Tagih</th>
                    <th>Tanggal Pembayaran</th>
                    <th>Jumlah Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pembayaran as $item)
                    <tr>
                        <td>{{ $item->id_plg }}</td>
                        <td>{{ $item->nama_plg }}</td>
                        <td>{{ $item->alamat_plg }}</td>
                        <td>{{ $item->tgl_tagih_plg }}</td>
                        <td>{{ $item->tanggal_pembayaran ?? 'Belum Membayar' }}</td>
                        <td>{{ $item->jumlah_pembayaran ?? '-' }}</td>
                        <td>
                            <a href="{{ route('pelanggan.historypembayaran', $item->id_plg) }}" class="btn btn-info btn-sm">Lihat</a>
                            <!-- Tombol lain seperti edit atau delete bisa ditambahkan di sini -->
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center">Tidak ada pelanggan yang belum membayar bulan ini</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
