@extends($layout)

@section('konten')
    <div class="container mt-4">
        <!-- Filter Tanggal -->
        <form action="{{ route('perbaikan.rekapTeknisi') }}" method="GET" class="mb-3">
            @csrf
            <div class="row">
                <div class="col-md-5">
                    <div class="form-group" style="color: black;">
                        <label for="start_date">Tanggal Mulai</label>
                        <input type="date" id="start_date" name="start_date" class="form-control"
                            value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-md-5" style="color: black;">
                    <div class="form-group">
                        <label for="end_date">Tanggal Akhir</label>
                        <input type="date" id="end_date" name="end_date" class="form-control"
                            value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="filter_button">&nbsp;</label>
                        <button type="submit" class="btn btn-danger btn-block">Filter</button>
                    </div>
                </div>
            </div>
        </form>

        <!-- Menampilkan Total Perbaikan -->
        <div class="mb-3" style="color: black;">
            <h5>Total Perbaikan: {{ $totalPerbaikan }}</h5>
        </div>

        <!-- Tabel Rekap Data Teknisi -->
        <table class="table table-bordered font-weight-bold" style="color: black;">
            <thead class="table table-danger">
                <tr class="text text-black font-weight-bold" style="color: black;">
                    <th style="color: black;" class="font font-weight-bold">Teknisi</th>
                    <th class="text text-black font-weight-bold" style="color: black;">Total Perbaikan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekap as $data)
                    <tr class="font-weight-bold" style="color: black">
                        <td>{{ $data->teknisi }}</td>
                        <td>{{ $data->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Tombol Cetak PDF -->
        <div class="text-center mt-4" style="color: black;">
            <form action="{{ route('perbaikan.printRekapTeknisi') }}" method="POST">
                @csrf
                <input type="hidden" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                <input type="hidden" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                <button type="submit" class="btn btn-danger">Cetak PDF</button>
            </form>
        </div>
    </div>
    <div>
        <table class="table table-responsive table-bordered  " style="color: black;">
            <thead class="table table-primary " style="color: black;">
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No Telpon</th>
                    <th>Teknisi</th>
                    <th>Keterangan</th>
                    <th>Tanggal</th>
                    <th>Detail</th>


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
                        <td>{{ $item->teknisi }}</td>
                        <td>{{ $item->keterangan_plg }}</td>
                        <td>{{ $item->created_at }}</td>

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
