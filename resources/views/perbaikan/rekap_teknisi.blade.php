@extends($layout)

@section('konten')
    <div class="container mt-4">
        <!-- Filter Tanggal -->
        <form action="{{ route('perbaikan.rekapTeknisi') }}" method="GET" class="mb-3">
            <div class="input-group" style="color: black;">
                <input type="text" name="search" id="search" class="form-control font-weight-bold" style="color: black;"
                    value="{{ request('search') }}" placeholder="Pencarian">
            </div>

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

        <!-- Wrapper untuk menempatkan elemen secara sejajar -->
        <div class="d-flex justify-content-between align-items-center" style="color: black;">
            <!-- Menampilkan Total Perbaikan -->
            <div>
                <h5>Total Perbaikan: {{ $totalPerbaikan }}</h5>
            </div>

            <!-- Tombol Cetak PDF -->
            <div class="mb-2">
                <form action="{{ route('perbaikan.printRekapTeknisi') }}" method="POST">
                    @csrf
                    <input type="hidden" name="start_date"
                        value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                    <input type="hidden" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                    <button type="submit" class="btn btn-danger">Cetak PDF</button>
                </form>
            </div>
        </div>


        <!-- Tabel Rekap Data Teknisi -->
        <table class="table table-bordered " style="color: black;">
            <thead class="table table-danger">
                <tr>
                    <th style="color: black;">Teknisi</th>
                    <th class="text text-black" style="color: black;">Total Perbaikan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekap as $data)
                    <tr class="font-weight-bold">
                        <td>{{ $data->teknisi }}</td>
                        <td>{{ $data->total }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <div>
            <table class="table table-bordered  " style="color: black;">
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


                    </tr>
                </thead>
                <tbody>
                    @forelse ($perbaikan as $no => $item)
                        <tr class="font-weight-bold">
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
    </div>
@endsection
