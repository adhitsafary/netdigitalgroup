@extends($layout)

@section('konten')
    <div class="container">
        <h1>Filter Pelanggan Berdasarkan Tanggal Tagih</h1>

        <th>
            <form class="filterForm" method="GET" action="{{ route('pelanggan.filterTagihindex') }}">
                <div class="form-group">
                    <select name="paket_plg" id="paket_plg" onchange="document.querySelector('.filterForm').submit();">
                        <option value="">Paket</option>
                        @for ($i = 1; $i <= 7; $i++)
                            <option value="{{ $i }}" {{ request('paket_plg') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                        <option value="vcr" {{ request('paket_plg') == 'vcr' ? 'selected' : '' }}>
                            vcr
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <select name="tgl_tagih_plg" id="tgl_tagih_plg"
                        onchange="document.querySelector('.filterForm').submit();">
                        <option value="">Tanggal Tagih</option>
                        @for ($i = 1; $i <= 32; $i++)
                            <option value="{{ $i }}" {{ request('tgl_tagih_plg') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
            </form>
        </th>
        <th>Harga Paket</th>


        @if (isset($pelanggan) && count($pelanggan) > 0)
            <h2 class="mt-5">Hasil Filter Tanggal Tagih: {{ $tanggal }}</h2>
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Pelanggan</th>
                        <th>Alamat</th>
                        <th>No Telepon</th>
                        <th>Tanggal Tagih</th>
                        <th>Status Pembayaran</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pelanggan as $plg)
                        <tr>
                            <td>{{ $plg->id_plg }}</td>
                            <td>{{ $plg->nama_plg }}</td>
                            <td>{{ $plg->alamat_plg }}</td>
                            <td>{{ $plg->no_telepon_plg }}</td>
                            <td>{{ $plg->tgl_tagih_plg }}</td>
                            <td>{{ $plg->status_pembayaran }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="mt-5">Tidak ada data pelanggan untuk tanggal tagih tersebut.</p>
        @endif
    </div>
@endsection
