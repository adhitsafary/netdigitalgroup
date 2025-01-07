@extends($layout)

@section('konten')
    <div class="pl-5 pr-5">
        <div class="mb-4">
            <!-- Form Filter dan Pencarian -->
            <div class="row mb-4">
                <div class="col-md-9">
                    <form action="{{ route('perbaikan.index') }}" method="GET" class="form-inline">
                        <div class="input-group" style="color: black;">
                            <input style="color: black;" type="text" name="search" id="search"
                                class="form-control font-weight-bold" value="{{ request('search') }}"
                                placeholder="Pencarian">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-danger">Cari</button>
                            </div>

                        </div>
                    </form>
                </div>

                <div class="col-md-3 text-right">
                    <a href="/perbaikan/create"  class="btn btn-danger">Buat Perbaikan</a>
                    <a href="/rekap-teknisi" class="btn btn-danger ">Rekap Bulanan Teknisi</a>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Tabel Perbaikan -->
        <table class="table table-bordered table-responsive " style="color: black;">
            <thead class="table table-danger" style="color: black;">
                <tr>
                    <th>No</th>
                    <th>ID Pel</th>
                    <th>Nama Pel</th>
                    <th>Alamat</th>
                    <th>No Hp</th>
                    <th>Paket</th>
                    <th>Odp</th>
                    <th>Maps</th>
                    <th>Teknisi</th>
                    <th>Keterangan</th>
                    <th>
                        <!-- Link untuk sorting -->
                        <a
                            href="{{ route('perbaikan.index', array_merge(request()->except('sort'), ['sort' => $sort === 'asc' ? 'desc' : 'asc'])) }}">
                            Tanggal
                            @if ($sort === 'asc')
                                &uarr; <!-- Icon untuk sorting ascending -->
                            @else
                                &darr; <!-- Icon untuk sorting descending -->
                            @endif
                        </a>
                    </th>
                    <th>Aksi</th>
                    <th>Status</th>
                    <th>Selesai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perbaikan as $no => $item)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $item->id_plg }}</td>
                        <td>{{ $item->nama_plg }}</td>
                        <td>{{ $item->alamat_plg }}</td>
                        <td>{{ $item->no_telepon_plg }}</td>
                        <td>{{ $item->paket_plg }}</td>
                        <td>{{ $item->odp }}</td>
                        <td>{{ $item->maps }}</td>
                        <td>{{ $item->teknisi }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>
                            <a href="{{ route('perbaikan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('perbaikan.destroy', $item->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </td>
                        <td>{{ ucfirst($item->status) }}</td> <!-- Menampilkan status -->
                        <td>
                            @if ($item->status == 'Proses')
                                <!-- Hanya tampilkan tombol jika statusnya Proses -->
                                <form action="{{ route('perbaikan.selesai', $item->id) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Selesai</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">Tidak ada data ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    </div>
@endsection
