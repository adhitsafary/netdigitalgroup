@extends($layout)

@section('konten')
    <div class="  pl-5 pr-5 mb-4">
        <!-- Form Filter dan Pencarian -->
        <div class="row align-items-center">
            <div class="">
                <!-- Form Pencarian -->

                <div class="mb-2 ml-3">
                    <!-- Tampilkan Hasil Filter -->
                    <div>
                        <!-- Tombol Total Sudah Bayar -->
                        <button class="btn btn-info mt-2  btn-lg  font-weight-bold"
                            onclick="copyToClipboard('Total Sudah Bayar: {{ $totalSudahBayar }} (Rp {{ number_format($totalPembayaranSudahBayar, 0, ',', '.') }})')">
                            Total Sudah Bayar: {{ $totalSudahBayar }} (Rp
                            {{ number_format($totalPembayaranSudahBayar, 0, ',', '.') }})
                        </button>

                        <!-- Tombol Total Belum Bayar -->
                        <button class="btn btn-warning mt-2  btn-lg  font-weight-bold"
                            onclick="copyToClipboard('Total Belum Bayar: {{ $totalBelumBayar }} (Rp {{ number_format($totalPembayaranBelumBayar, 0, ',', '.') }})')">
                            Total Belum Bayar: {{ $totalBelumBayar }} (Rp
                            {{ number_format($totalPembayaranBelumBayar, 0, ',', '.') }})
                        </button>

                        <!-- Tombol Total Isolir -->

                        <a href="{{ route('pelanggan.isolir') }}" class="btn btn-danger mt-2  btn-lg  font-weight-bold"> Total
                            Isolir: {{ $totalIsolir }} (Rp
                            {{ number_format($totalPembayaranIsolir, 0, ',', '.') }})</a>

                        <!-- Tombol Total Block -->
                        <a href="{{ route('pelanggan.block') }}" class="btn btn-danger mt-2  btn-lg  font-weight-bold">
                            Total Block: {{ $totalBlock }} (Rp {{ number_format($totalPembayaranBlock, 0, ',', '.') }})
                        </a>

                        <!-- Tombol Total Unblock -->
                        <a href="{{ route('pelanggan.unblock') }}" class="btn btn-success mt-2  btn-lg  font-weight-bold">
                            Total Unblock: {{ $totalUnblock }} (Rp
                            {{ number_format($totalPembayaranUnblock, 0, ',', '.') }})
                        </a>

                        <button class="btn btn-primary btn-lg mt-2 font-weight-bold"
                            style="cursor: default; background: linear-gradient(45deg, #007bff, #007bff); color: #ffffff;"
                            onclick="copyToClipboard('Pelanggan (Filter): Rp {{ number_format($totalJumlahPembayaranfilter, 0, ',', '.') }} || User: {{ number_format($totalPelangganfilter, 0, ',', '.') }}')">
                            Total Filter: Rp {{ number_format($totalJumlahPembayaranfilter, 0, ',', '.') }} || User
                            :
                            {{ number_format($totalPelangganfilter, 0, ',', '.') }}
                        </button>

                        <!-- Tombol Total Keseluruhan -->
                        <button class="btn btn-primary btn-lg mt-2 font-weight-bold"
                            style="cursor: default; background: linear-gradient(45deg, #ecc100, #ecc100); color: #000000;">
                            Total Keseluruhan : Rp {{ number_format($totalJumlahPembayaranKeseluruhan, 0, ',', '.') }} ||
                            User :
                            {{ number_format($totalPelangganKeseluruhan, 0, ',', '.') }}
                        </button>

                        <!-- Tombol Sisa Pembayaran -->
                        <button class="btn btn-primary btn-lg mt-2 font-weight-bold"
                            style="cursor: default; background: linear-gradient(45deg, #ff0000, #ff0000); color: #ffffff;"
                            onclick="copyToClipboard('Tersisa: Rp {{ number_format($sisaPembayaran, 0, ',', '.') }} || User: {{ number_format($sisaUser, 0, ',', '.') }}')">
                            Tersisa : Rp {{ number_format($sisaPembayaran, 0, ',', '.') }} || User :
                            {{ number_format($sisaUser, 0, ',', '.') }}
                        </button>

                        <!-- Tombol Total Masuk -->
                        <button class="btn btn-primary mt-2 btn-lg  font-weight-bold"
                            style="cursor: default; background: linear-gradient(45deg, rgb(32, 190, 0), rgb(32, 190, 0)); color: #ffffff;"
                            onclick="copyToClipboard('Total Masuk: Rp {{ number_format($totalJumlahPembayaran, 0, ',', '.') }} || User: {{ number_format($totalPelangganBayar, 0, ',', '.') }}')">
                            Total Masuk : Rp {{ number_format($totalJumlahPembayaran, 0, ',', '.') }} || User :
                            {{ number_format($totalPelangganBayar, 0, ',', '.') }}
                        </button>
                    </div>

                    <!-- Tambahkan script JavaScript untuk menyalin data ke clipboard -->
                    <script>
                        function copyToClipboard(text) {
                            // Membuat elemen input sementara untuk menyalin teks
                            var tempInput = document.createElement('input');
                            tempInput.value = text;
                            document.body.appendChild(tempInput);
                            tempInput.select();
                            document.execCommand('copy');
                            document.body.removeChild(tempInput);

                            // Tampilkan alert sebagai notifikasi
                            alert('Teks disalin: ' + text);
                        }
                    </script>

                </div>
            </div>
        </div>
        <div class="d-flex align-items-center justify-content-between mt-2">
            <form action="{{ route('pelanggan.index') }}" method="GET" class="form-inline d-flex" style="color: black;">
                <div class="input-group" style="color: black;">
                    <input type="text" name="search" id="search" class="form-control font-weight-bold"
                        style="color: black;" value="{{ request('search') }}" placeholder="Pencarian">
                </div>
                <button type="submit" name="action" value="search" class="btn btn-danger ml-2">Cari</button>
            </form>

            <div class="mx-auto text-center mr-3">
                <h3 class="font-weight-bold" style="color: black;">Data Pelanggan</h3>
            </div>
            <div class="col-md-3 text-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Ekspor
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('pelanggan.export', ['format' => 'pdf', 'tgl_tagih_plg' => request('tgl_tagih_plg')]) }}"
                            class="dropdown-item">PDF</a>
                        <a href="{{ route('pelanggan.export', ['format' => 'excel', 'tgl_tagih_plg' => request('tgl_tagih_plg')]) }}"
                            class="dropdown-item">Excel</a>
                    </div>
                </div>
            </div>

        </div>


        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if (session('alert'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('alert') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Tampilkan jumlah total pembayaran dan jumlah pelanggan -->


        <div class="">

            <th class="mt-2">
                <form action="{{ route('pelanggan.index') }}" method="GET">
                    <select name="tgl_tagih_plg" id="tgl_tagih_plg">
                        <option value="">Tanggal Tagih</option>
                        @for ($i = 1; $i <= 33; $i++)
                            <option value="{{ $i }}"
                                {{ request('tgl_tagih_plg') == $i ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                    <select name="paket_plg" id="paket_plg">
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

                    <select name="harga_paket" id="harga_paket">
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

                    <select name="status_pembayaran">
                        <option value="">Semua Status</option>
                        <option value="sudah_bayar">Sudah Bayar</option>
                        <option value="belum_bayar">Belum Bayar</option>
                    </select>

                    <input type="date" id="updated_at" name="updated_at" value="{{ request()->get('updated_at') }}">
                    <button type="submit" class="btn btn-primary ">Filter</button>
                </form>
            </th>


            <table class="table table-bordered table-responsive " style="color: black;">
                <thead class="table table-danger " style="color: black;">
                    <tr class="font-weight-bold">
                        <th class="">No</th>
                        <th>ID</th>
                        <th>
                            <form action="{{ route('pelanggan.index') }}" method="GET">
                                <!-- Filter lainnya... -->

                                <label for="order_nama">Nama</label><br>
                                <select name="order_nama" id="order_nama">
                                    <option value="asc">A-Z</option>
                                    <option value="desc">Z-A</option>
                                </select>

                                <button type="submit">Filter</button>
                            </form>

                        </th>
                        <th>
                            <form action="{{ route('pelanggan.index') }}" method="GET">
                                <!-- Filter lainnya... -->

                                <label for="order_alamat">Alamat</label><br>
                                <select name="order_alamat" id="order_alamat">
                                    <option value="asc">A-Z</option>
                                    <option value="desc">Z-A</option>
                                </select>

                                <button type="submit">Filter</button>
                            </form>

                        </th>



                        <th>Bayar</th>
                        <th>No Telpon</th>
                        <th>Aktivasi</th>
                        <th>
                            <form class="filterForm" method="GET" action="{{ route('pelanggan.index') }}">
                                <label for="order_keterangan">Paket</label><br>
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
                            <form class="filterForm" method="GET" action="{{ route('pelanggan.index') }}">
                                <label for="order_keterangan">Harga</label><br>
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
                            <form class="filterForm" method="GET" action="{{ route('pelanggan.index') }}">
                                <label for="order_keterangan">Tanggal Tagih</label><br>
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
                        <th>
                            <form action="{{ route('pelanggan.index') }}" method="GET">
                                <!-- Filter lainnya... -->

                                <label for="order_keterangan">Keterangan</label><br>
                                <select name="order_keterangan" id="order_keterangan">
                                    <option value="asc">A-Z</option>
                                    <option value="desc">Z-A</option>
                                </select>

                                <button type="submit">Filter</button>
                            </form>

                        </th>
                        <th>Bayar Terakhir</th>

                        <th>
                            <div class="d-flex justify-content-between align-items-center">
                                <!-- Label Status -->
                                <span>Status Pembayaran</span>
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
                                                <option value="UnBlock"
                                                    {{ request('status_pembayaran') == 'UnBlock' ? 'selected' : '' }}>
                                                    UnBlock
                                                </option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </th>

                        <th>Detail</th>


                    </tr>
                </thead>
                <tbody>
                    @forelse ($pelanggan as $no => $item)
                        <tr class="font-weight-bold">

                            <td>{{ ($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration }}</td>

                            <td>{{ $item->id_plg }}</td>
                            <td>{{ $item->nama_plg }}</td>
                            <td>{{ $item->alamat_plg }}</td>
                            <td>
                                <a href="#" class="btn btn-success btn-sm"
                                    onclick="showBayarModal({{ $item->id }}, '{{ $item->nama_plg }}', {{ $item->harga_paket }})">Bayar</a>
                            </td>
                            <td>{{ $item->no_telepon_plg }}</td>
                            <td>{{ $item->aktivasi_plg }}</td>
                            <td>{{ $item->paket_plg }}</td>
                            <td>{{ number_format($item->harga_paket, 0, ',', '.') }}</td>
                            <td>{{ $item->tgl_tagih_plg }}</td>

                            <td>{{ $item->keterangan_plg }}</td>
                            <!--  <td>
                                                                                                                                {{ optional($item->pembayaranTerakhir)->tanggal_pembayaran
                                                                                                                                    ? \Carbon\Carbon::parse($item->pembayaranTerakhir->tanggal_pembayaran)->locale('id')->settings(['formatFunction' => 'translatedFormat'])->translatedFormat('l, d F Y H:i:s')
                                                                                                                                    : 'Belum Ada pembayaran' }}
                                                                                                                            </td> -->

                            <td>
                                {{ optional($item->pembayaranTerakhir)->tanggal_pembayaran
                                    ? \Carbon\Carbon::parse($item->pembayaranTerakhir->tanggal_pembayaran)->locale('id')->isoFormat('MMMM Y')
                                    : '-' }}
                            </td>
                            <td>
                                <select name="tanggal_pembayaran" class="form-control ml-1 mr-1 mb-2"
                                    onchange="this.form.submit()">
                                    <option value="">Riwayat Pembayaran</option>
                                    @foreach ($item->pembayaran as $pembayaran)
                                        @php
                                            $tanggalPembayaran = \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran);
                                            // Periksa apakah tanggal pembayaran kurang dari bulan sekarang
                                            $isDanger = $tanggalPembayaran->lessThan(now()->startOfMonth());
                                        @endphp
                                        <option value="{{ $tanggalPembayaran->format('Y-m-d') }}">
                                            {{ $tanggalPembayaran->locale('id')->isoFormat('MMMM Y') }}
                                        </option>
                                    @endforeach
                                    @if (!$item->pembayaran->count())
                                        <option value="">Belum Ada Pembayaran</option>
                                    @endif
                                </select>

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
                                                    <label for="tanggal_pembayaran" class="form-label">Untuk Pembayaran
                                                        Bulan</label>
                                                    <input type="month" class="form-select" id="tanggal_pembayaran"
                                                        name="tanggal_pembayaran" placeholder="Pilih bulan">
                                                </div>



                                                <div class="mb-3">
                                                    <label for="metodeTransaksi" class="form-label">Metode
                                                        Transaksi</label>
                                                    <select class="form-select" id="metodeTransaksi"
                                                        name="metode_transaksi" required>
                                                        <option value="">Pilih metode</option>
                                                        <option value="TF">TF</option>
                                                        <option value="CASH">KANTOR</option>

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
