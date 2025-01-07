@extends('superadmin.layout_superadmin')

@section('konten')
    <div class="mb-4 m-r-4 ml-4">
        <!-- Form Filter dan Pencarian -->
        <form action="{{ route('kasbon.index') }}" method="GET" class="form-inline mb-4 ">
            <div class="input-group">
                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Pencarian">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>
        <h5 class="font font-weight-bold" style="color: black">Data Kasbon</h5>
        <table class=" table table-bordered  font-weight-bold" style="color: black;">
            <thead class="table table-primary font font-weight-bold" style="color: black;">
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Jumlah</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>

                </tr>
            </thead>
            <tbody>
                @forelse ($kasbon as $no => $item)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>{{ $item->tanggal }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td> <a href="{{ route('kasbon.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('kasbon.destroy', $item->id) }}" method="POST" class="d-inline-block">
                                @csrf
                                @method('DELETE')
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
