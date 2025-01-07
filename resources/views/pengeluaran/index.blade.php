@extends($layout)

@section('konten')
    <div class="container mb-4">
        <!-- Form Filter dan Pencarian -->
        <form action="{{ route('pengeluaran.index') }}" method="GET" class="form-inline mb-4 ">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Pencarian">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>

        <a href="/pengeluaran/create" class="btn btn-success">Buat Pengeluaran</a>
        <a href="/pemasukan" class="btn btn-success">Data pemasukan</a>
        <div style="display: flex; justify-content: center;" class="mb-3">

            <h5 style="color: black;" class="font font-weight-bold">Data Pengeluaran</h5>
        </div>

        <table class="table table-bordered" style="color: black;">
            <thead class="table table-primary " style="color: black;">
                <tr>
                    <th>No</th>
                    <th>Jumlah</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>

                </tr>
            </thead>
            <tbody>
                @forelse ($pengeluaran as $no => $item)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ number_format($item->jumlah) }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td> <a href="{{ route('pengeluaran.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST"
                                class="d-inline-block">
                                @csrf
                         
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="13" class="text-center">Tidak ada data ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
