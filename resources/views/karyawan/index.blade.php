@extends('superadmin.layout_superadmin')

@section('konten')
    <div class="container mb-4">
        <div class="row ml-0">
            <!-- Form Filter dan Pencarian -->

            <form action="{{ route('karyawan.index') }}" method="GET" class="form-inline mb-4 mr-3" >
                <div class="input-group">
                    <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}"
                        placeholder="Pencarian">
                    <div class="input-group-append">

                        <button type="submit" class="btn btn-primary">Cari</button>

                    </div>
                </div>
            </form>

            <a href="/masuk/superadmin/karyawan/create" class="mr-2">
                <button class="btn btn-primary">Tambah karyawan</button>
            </a>
        </div>

        <table class="table table-bordered font-weight-bold" style="color: black;">
            <thead class="table table-primary font-weight-bold" style="color: black;">
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>KTP</th>
                    <th>Alamat</th>
                    <th>No Telpon</th>
                    <th>Posisi</th>
                    <th>Mulai Kerja</th>

                    <th>Gaji</th>
                    <th>Tanggal Gajihan</th>

                    <th>Posisi</th>
                    <th>Aksi</th>

                </tr>
            </thead>
            <tbody>
                @forelse ($karyawan as $no => $item)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>
                            <img src="{{ asset($item->foto) }}" alt="Foto Karyawan" style="width: 50px; height: 50px;">
                        </td>

                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->ktp }}</td>
                        <td>{{ $item->alamat }}</td>
                        <td>{{ $item->no_telepon }}</td>
                        <td>{{ $item->posisi }}</td>
                        <td>{{ $item->mulai_kerja }}</td>
                        <td>{{ $item->gaji }}</td>
                        <td>{{ $item->tgl_gajihan }}</td>
                        <td>{{ $item->posisi }}</td>

                        <td>
                            <a href="{{ route('karyawan.detail', $item->id) }}" class="btn btn-warning btn-sm">Detail
                            </a>
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
