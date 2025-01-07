@extends('superadmin.layout_superadmin')

@section('konten')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Detail Karyawan</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5 style="color: black" class="fotn font-weight-bold">Informasi Karyawan</h5>
                        <ul class="list-group  font-weight-bold" style="color: black">
                            <li class="list-group-item">
                                <strong>Nama :</strong> {{ $karyawan->nama }}
                            </li>
                            <li class="list-group-item">
                                <strong>KTP :</strong> {{ $karyawan->ktp }}
                            </li>
                            <li class="list-group-item">
                                <strong>Alamat :</strong> {{ $karyawan->alamat }}
                            </li>
                            <li class="list-group-item">
                                <strong>No Telepon :</strong> {{ $karyawan->no_telepon }}
                            </li>
                            <li class="list-group-item">
                                <strong>Gaji : </strong>{{ number_format($karyawan->gaji, 0, ',', '.') }}
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5  style="color: black" class=" font-weight-bold">Detail Karyawan</h5>
                        <ul class="list-group  font-weight-bold" style="color: black">
                            <li class="list-group-item">
                                <strong>Posisi :</strong> {{ $karyawan->posisi }}
                            </li>
                            <li class="list-group-item">
                                <strong>Mulai Kerja :</strong> {{ $karyawan->mulai_kerja }}
                            </li>
                            <li class="list-group-item">
                                <strong>Keterangan :</strong> {{ $karyawan->keterangan }}
                            </li>
                            <li class="list-group-item">
                                <strong>Tanggal Gajihan :</strong> {{ $karyawan->tgl_gajihan }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Tabel Riwayat Kasbon -->
                <h5 class="mt-4 font-weight-bold" style=" color: black">Riwayat Kasbon</h5>
                <table class="table table-bordered font-weight-bold" style="color: black">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jumlah Kasbon</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kasbon as $item)
                            <tr>
                                <td>{{ $item->tanggal }}</td>
                                <td>{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                                <td>{{ $item->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                    <!-- Tabel Riwayat Kasbon -->
                    <h5 class="mt-4 font-weight-bold" style=" color: black">Hitungan Gaji</h5>
                    <table class="table table-bordered font-weight-bold" style="color: black">
                        <thead>
                            <tr>
                                <th>Total Gaji</th>
                                <th>Total Kasbon</th>
                                <th>Gaji Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ number_format($karyawan->gaji, 0, ',', '.') }}</td>
                                <td style="color: darkred" class="font font-weight-bold"> {{ number_format($totalKasbon, 0, ',', '.') }}</td>
                                <td> {{ number_format($karyawan->gaji - $totalKasbon, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    


            </div>
            <div class="card-footer text-right">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('karyawan.edit', $karyawan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ route('kasbon.create', $karyawan->id) }}" class="btn btn-sm btn-primary">Tambah
                            Kasbon</a>
                        <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST"
                            class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                        </form>
                    </div>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                </div>
            </div>
        </div>
    </div>
@endsection
