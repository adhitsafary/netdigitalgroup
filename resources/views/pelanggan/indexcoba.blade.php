@extends($layout)

@section('konten')
    <div class="  pl-5 pr-5 mb-4">
        <!-- Form Filter dan Pencarian -->
        <div class="row align-items-center">
            <div class="col-md-6" style="color: black">
                <!-- Form Pencarian -->

                <div class="mb-2">
                    
                    <button  class="btn btn-primary btn-lg mt-2 font-weight-bold"
                    style="cursor: default; background: linear-gradient(45deg, #eeca00, #ff6600d8); color: #ffffff;">
                    Total keseluruhan : {{ number_format($totalJumlahPembayaranKeseluruhan, 0, ',', '.') }} || User : {{ number_format($totalPelangganKeseluruhan, 0, ',', '.') }}
                    </button>
                    <button  class="btn btn-primary btn-lg mt-2 font-weight-bold"
                    style="cursor: default; background: linear-gradient(45deg, #ff0000, #ffc02d); color: #ffffff;">
                    Tersisa : Rp {{ number_format($sisaPembayaran, 0, ',', '.') }} || User : {{ number_format($sisaUser, 0, ',', '.') }}
                    </button>
                    <button  class="btn btn-primary btn-lg mt-2 font-weight-bold"
                    style="cursor: default; background: linear-gradient(45deg, #007bff, #00ff6a); color: #ffffff;">
                    Pembayaran(Filter) : Rp {{ number_format($totalJumlahPembayaranfilter, 0, ',', '.') }} || User : {{ number_format($totalPelangganfilter, 0, ',', '.') }}
                    </button>
                    <button  class="btn btn-primary btn-lg mt-2 font-weight-bold"
                    style="cursor: default; background: linear-gradient(45deg, rgb(32, 190, 0), #ffbb00); color: #ffffff;">
                    Total Masuk : {{ number_format($totalJumlahPembayaran, 0, ',', '.') }} || User : {{ number_format($totalPelangganBayar, 0, ',', '.') }}
                    </button>


                </div>

                <form action="{{ route('pelanggan.index') }}" method="GET" class="form-inline" style="color: black">
                    <!-- Input Pencarian -->
                    <div class="input-group" style="color: black">
                        <input type="text" name="search" id="search" class="form-control font-weight-bold"
                            style="color: black" value="{{ request('search') }}" placeholder="Pencarian">
                    </div>
                    <!-- Tombol Cari -->
                    <button type="submit" name="action" value="search" class="btn btn-danger ml-2">Cari</button>
                    
                    
                </form>
            </div>
        </div>
        <div class="text-right mb-2">

            <!-- Teks Data isolir -->
            <a href="/isolir" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Data Isolir</a>
            <div class="btn btn-danger btn-lg mt-2" data-toggle="modal" data-target="#filterModal"
                style="cursor: default; background: linear-gradient(45deg, #ff0000, #ffc02d); color: #ffffff;">
                Data Pelanggan
            </div>
            <a href="/pelangganof" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Data Pelanggan Off</a>
            <a href="/pembayaran" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Pembayaran</a>
            <a href="/perbaikan" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Perbaikan/PSB</a>
            <a href="/pemasukan" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Pemasukan/Pengeluaran</a>
            <a href="/rekap-harian/" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Rekap Harian</a>
            <a href="/rekap_pemasangan/" class="btn btn-primary btn-lg mt-2"
                style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                Rekap PSB</a>



            
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

            <!-- Tampilkan jumlah total pembayaran dan jumlah pelanggan -->
       

        <div class="">
            <table class="table table-bordered table-responsive " style="color: black;">
                <thead class="table table-danger " style="color: black;">
                    <tr class="font-weight-bold">
                        <th class="">No</th>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>No Telpon</th>
                        <th>Aktivasi</th>
                        <th>
                            <form class="filterForm" method="GET" action="{{ route('pelanggan.filterTagihindex') }}">
                                <div class="form-group">
                                    <select name="paket_plg" id="paket_plg" onchange="this.form.submit();">
                                        <option value="">Paket</option>
                                        @for ($i = 1; $i <= 7; $i++)
                                            <option value="{{ $i }}"
                                                {{ request('paket_plg') == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                        <option value="vcr" {{ request('paket_plg') == 'vcr' ? 'selected' : '' }}>
                                            vcr
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </th>

                        <th>
                            <form class="filterForm" method="GET" action="{{ route('pelanggan.filterTagihindex') }}">
                                <div class="form-group">
                                    <select name="harga_paket" id="harga_paket" onchange="this.form.submit();">
                                        <option value="">Harga</option>
                                        <option value="50000"
                                            {{ request('jumlah_pembayaran') == '50000' ? 'selected' : '' }}>
                                            {{ number_format(50000, 0, ',', '.') }}
                                        </option>
                                        <option value="75000"
                                            {{ request('jumlah_pembayaran') == '75000' ? 'selected' : '' }}>
                                            {{ number_format(75000, 0, ',', '.') }}
                                        </option>
                                        <option value="100000"
                                            {{ request('jumlah_pembayaran') == '100000' ? 'selected' : '' }}>
                                            {{ number_format(100000, 0, ',', '.') }}
                                        </option>
                                        <option value="105000"
                                            {{ request('jumlah_pembayaran') == '105000' ? 'selected' : '' }}>
                                            {{ number_format(105000, 0, ',', '.') }}
                                        </option>
                                        <option value="115000"
                                            {{ request('jumlah_pembayaran') == '115000' ? 'selected' : '' }}>
                                            {{ number_format(115000, 0, ',', '.') }}
                                        </option>

                                        <option value="120000"
                                            {{ request('jumlah_pembayaran') == '120000' ? 'selected' : '' }}>
                                            {{ number_format(120000, 0, ',', '.') }}
                                        </option>
                                        <option value="125000"
                                            {{ request('jumlah_pembayaran') == '125000' ? 'selected' : '' }}>
                                            {{ number_format(125000, 0, ',', '.') }}
                                        </option>
                                        <option value="150000"
                                            {{ request('jumlah_pembayaran') == '150000' ? 'selected' : '' }}>
                                            {{ number_format(150000, 0, ',', '.') }}
                                        </option>
                                        <option value="165000"
                                            {{ request('jumlah_pembayaran') == '165000' ? 'selected' : '' }}>
                                            {{ number_format(165000, 0, ',', '.') }}
                                        </option>
                                        <option value="175000"
                                            {{ request('jumlah_pembayaran') == '175000' ? 'selected' : '' }}>
                                            {{ number_format(175000, 0, ',', '.') }}
                                        </option>
                                        <option value="205000"
                                            {{ request('jumlah_pembayaran') == '205000' ? 'selected' : '' }}>
                                            {{ number_format(205000, 0, ',', '.') }}
                                        </option>
                                        <option value="250000"
                                            {{ request('jumlah_pembayaran') == '250000' ? 'selected' : '' }}>
                                            {{ number_format(250000, 0, ',', '.') }}
                                        </option>
                                        <option value="265000"
                                            {{ request('jumlah_pembayaran') == '265000' ? 'selected' : '' }}>
                                            {{ number_format(265000, 0, ',', '.') }}
                                        </option>
                                        <option value="305000"
                                            {{ request('jumlah_pembayaran') == '305000' ? 'selected' : '' }}>
                                            {{ number_format(305000, 0, ',', '.') }}
                                        </option>
                                        <option value="750000"
                                            {{ request('jumlah_pembayaran') == '750000' ? 'selected' : '' }}>
                                            {{ number_format(750000, 0, ',', '.') }}
                                        </option>
                                        <option value="vcr"
                                            {{ request('jumlah_pembayaran') == 'vcr' ? 'selected' : '' }}>
                                            vcr
                                        </option>
                                    </select>
                                </div>
                            </form>
                        </th>

                        <th>
                            <form class="filterForm" method="GET" action="{{ route('pelanggan.filterTagihindex') }}">
                                <div class="form-group">
                                    <select name="tgl_tagih_plg" id="tgl_tagih_plg" onchange="this.form.submit();">
                                        <option value="">Tanggal Tagih</option>
                                        @for ($i = 1; $i <= 33; $i++)
                                            <option value="{{ $i }}"
                                                {{ request('tgl_tagih_plg') == $i ? 'selected' : '' }}>
                                                {{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </form>
                        </th>
                        <th>Keterangan</th>
                        <th>
                            Terakhir Bayar
                        </th>

                        <th>
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Label Status -->
                                <span>Status</span>

                                <!-- Form Filter -->
                                <div class="col-md-3 text-right">
                                    <form action="{{ route('pelanggan.index') }}" method="GET" class="form-inline"
                                        id="filterForm">
                                        <div class="input-group">
                                            <select name="status_pembayaran" id="status_pembayaran" class="form-control"
                                                onchange="document.getElementById('filterForm').submit();">
                                                <option value="">Semua</option>
                                                <option value="belum_bayar"
                                                    {{ request('status_pembayaran') == 'belum_bayar' ? 'selected' : '' }}>
                                                    Belum Bayar
                                                </option>
                                                <option value="sudah_bayar"
                                                    {{ request('status_pembayaran') == 'sudah_bayar' ? 'selected' : '' }}>
                                                    Sudah Bayar
                                                </option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </th>

                        <th>Detail</th>
                        <th>Bayar</th>
                        <th>Isolir</th>
                        <!-- <th>Riwayat pembayaran</th> -->
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pelanggan as $no => $item)
                        <tr class="font-weight-bold">

                            <td>{{ ($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration }}</td>

                            <td>{{ $item->id_plg }}</td>
                            <td>{{ $item->nama_plg }}</td>
                            <td>{{ $item->alamat_plg }}</td>
                            <td>{{ $item->no_telepon_plg }}</td>
                            <td>{{ $item->aktivasi_plg }}</td>
                            <td>{{ $item->paket_plg }}</td>
                            <td>{{ number_format($item->harga_paket, 0, ',', '.') }}</td>
                            <td>{{ $item->tgl_tagih_plg }}</td>

                            <td>{{ $item->keterangan_plg }}</td>
                            <td>
                                {{ optional($item->pembayaranTerakhir)->created_at
                                    ? \Carbon\Carbon::parse($item->pembayaranTerakhir->created_at)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->translatedFormat('l, d F Y H:i:s')
                                    : 'Belum Ada pembayaran' }}
                            </td>

                            <td>
                                <form action="{{ route('pelanggan.updateStatus', $item->id) }}" method="POST"
                                    class="form-inline">
                                    @csrf
                                    <select name="status_pembayaran" class="form-control" onchange="this.form.submit()">
                                        <option value="Belum Bayar"
                                            {{ strcasecmp($item->status_pembayaran, 'belum bayar') === 0 ? 'selected' : '' }}>
                                            Belum Bayar
                                        </option>
                                        <option value="Sudah Bayar"
                                            {{ strcasecmp($item->status_pembayaran, 'sudah bayar') === 0 ? 'selected' : '' }}>
                                            Sudah Bayar
                                        </option>
                                    </select>
                                </form>

                                <span
                                    class="badge {{ strcasecmp($item->status_pembayaran, 'Sudah Bayar') === 0 ? 'bg-success' : 'bg-danger' }} text-white ml-2"
                                    style="padding: 0.5em 1em; font-size: 1.1em;">
                                    {{ $item->status_pembayaran }}
                                </span>
                            </td>


                            <td>
                                <a href="{{ route('pelanggan.detail', $item->id) }}"
                                    class="btn btn-warning btn-sm">Detail</a>
                            </td>
                            <!-- Tombol Bayar -->
                            <td>
                                <a href="#" class="btn btn-success btn-sm"
                                    onclick="showBayarModal({{ $item->id }}, '{{ $item->nama_plg }}', {{ $item->harga_paket }})">Bayar</a>
                            </td>
                            <!-- Modal Bayar -->
                            <div class="modal fade" id="bayarModal" tabindex="-1" aria-labelledby="bayarModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="bayarModalLabel">Pembayaran</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <!-- Modal Form -->
                                        <form id="bayarForm" method="POST">
                                            @csrf
                                            <input type="hidden" name="id" id="pelangganId">
                                            <div class="modal-body">
                                                <!-- Input Tanggal Pembayaran -->


                                                <div class="mb-3">
                                                    <label for="metodeTransaksi" class="form-label">Metode
                                                        Transaksi</label>
                                                    <select class="form-select" id="metodeTransaksi"
                                                        name="metode_transaksi" required>
                                                        <option value="">Pilih metode</option>
                                                        <option value="CASH">Cash</option>
                                                        <option value="TF">Transfer</option>
                                                    </select>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="keterangan_plg" class="form-label">Keterangan
                                                        Pembayaran Pelanggan</label>
                                                    <input type="text" class="form-control" id="keterangan_plg"
                                                        name="keterangan_plg">
                                                </div>

                                                <!-- Detail Pembayaran -->
                                                <div class="mb-3">
                                                    <p id="pembayaranDetails"></p>
                                                </div>
                                            </div>

                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="submit" class="btn btn-primary">Bayar</button>
                                            </div>
                                        </form>


                                    </div>
                                </div>
                            </div>

                            <td>
                                <form action="{{ route('pelanggan.toIsolir', $item->id) }}" method="POST"
                                    style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Isolir</button>
                                </form>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="19" class="text-center">Tidak ada data ditemukan</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>

        </div>
        <div class="d-flex justify-content-center">
            {{ $pelanggan->links('pagination::bootstrap-4') }}
        </div>
    @endsection
    <script>
        function showBayarModal(id, namaPlg, hargaPaket) {
            document.getElementById('pelangganId').value = id;
            document.getElementById('pembayaranDetails').innerText =
                `Nama Pelanggan: ${namaPlg}\nHarga Paket: Rp. ${hargaPaket}`;

            var form = document.getElementById('bayarForm');
            form.action = `/pelanggan/${id}/bayar`; // Set action URL with the ID

            var bayarModal = new bootstrap.Modal(document.getElementById('bayarModal'));
            bayarModal.show();
        }
    </script>
