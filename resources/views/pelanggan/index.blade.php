@extends($layout)

@section('konten')
<div class="  pl-5 pr-5 mb-4">
    <!-- Form Filter dan Pencarian -->
    <div class="row align-items-center">
        <table class="table table-bordered mt-2">
            <thead class="custom-cell head">
                <tr>
                    <th>Total Filter</th>
                    <th>Total Sudah Bayar</th>
                    <th>Total Belum Bayar</th>
                    <th>Total Isolir</th>
                <!-- <th>Total Block</th>
                    <th>Total Unblock</th> -->
                    <th>Total Keseluruhan</th>
                    <th>Tersisa</th>
                    <th>Total Masuk</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="custom-cell primary">
                        Rp {{ number_format($totalJumlahPembayaranfilter, 0, ',', '.') }} User:
                        {{ number_format($totalPelangganfilter, 0, ',', '.') }}
                    </td>

                    <td class="custom-cell info">
                        Rp {{ number_format($totalPembayaranSudahBayar, 0, ',', '.') }} User: {{ $totalSudahBayar }}
                    </td>

                    <td class="custom-cell warning">
                        Rp {{ number_format($totalPembayaranBelumBayar, 0, ',', '.') }} User: {{ $totalBelumBayar }}
                    </td>

                    <td class="custom-cell danger">
                        <a href="{{ route('pelanggan.isolir') }}"> Rp
                            {{ number_format($totalPembayaranIsolir, 0, ',', '.') }} User: {{ $totalIsolir }}</a>
                    </td>

               <!--     <td class="custom-cell danger">
                        <a href="{{ route('pelanggan.block') }}"> Rp
                            {{ number_format($totalPembayaranBlock, 0, ',', '.') }} User: {{ $totalBlock }} </a>
                    </td>

                    <td class="custom-cell success">
                        <a href="{{ route('pelanggan.unblock') }}"> Rp
                            {{ number_format($totalPembayaranUnblock, 0, ',', '.') }} User: {{ $totalUnblock }} </a>
                    </td> -->



                    <td class="custom-cell primary-yellow">
                        Rp {{ number_format($totalJumlahPembayaranKeseluruhan, 0, ',', '.') }} User:
                        {{ number_format($totalPelangganKeseluruhan, 0, ',', '.') }}
                    </td>
                    <td class="custom-cell primary-red">
                        Rp {{ number_format($sisaPembayaran, 0, ',', '.') }} User:
                        {{ number_format($sisaUser, 0, ',', '.') }}
                    </td>

                    <td class="custom-cell primary-green">
                        Rp {{ number_format($totalJumlahPembayaran, 0, ',', '.') }} User:
                        {{ number_format($totalPelangganBayar, 0, ',', '.') }}
                    </td>


                </tr>
            </tbody>
        </table>

        <style>
            .custom-cell {
                padding: 10px;
                text-align: center;
                font-size: 1.0em;
                font-weight: bold;
                cursor: pointer;
                color: white;
            }

            .custom-cell.head {
                background: #530096;
                /* Biru */
            }

            .custom-cell.info {
                background: #17a2b8;
                /* Biru */
            }

            .custom-cell.warning {
                background: #ffc107;
                /* Kuning */
                color: black;
            }

            .custom-cell.danger {
                background: #dc3545;
                /* Merah */
            }

            .custom-cell.success {
                background: #28a745;
                /* Hijau */
            }

            .custom-cell.primary {
                background: #007bff;
                /* Biru tua */
            }

            .custom-cell.primary-yellow {
                background: #ecc100;
                /* Kuning terang */
                color: black;
            }

            .custom-cell.primary-red {
                background: #ff0000;
                /* Merah terang */
            }

            .custom-cell.primary-green {
                background: rgb(32, 190, 0);
                /* Hijau terang */
            }

            .table-bordered {
                border: 1px solid #dee2e6;
                width: 100%;
            }

            .table th,
            .table td {
                border: 1px solid #dee2e6;
                vertical-align: middle;
            }

            .table {
                width: 100%;
                table-layout: fixed;
                /* Membuat lebar kolom rata */
            }

            a {
                color: white;
                text-decoration: none;
            }

            a:hover {
                text-decoration: underline;
            }
        </style>

    </div>

    <!-- End Form Filter dan pencarian -->

    <div class="d-flex align-items-center justify-content-between mt-2">
        <form action="{{ route('pelanggan.index') }}" method="GET" class="form-inline d-flex" style="color: black;">
            <div class="input-group" style="color: black;">
                <input type="text" name="search" id="search" class="form-control font-weight-bold" style="color: black;"
                    value="{{ request('search') }}" placeholder="Pencarian">
            </div>
            <button type="submit" name="action" value="search" class="btn btn-danger ml-2">Cari</button>
        </form>

        <div class="mx-auto text-center mr-3">
            <h3 class="font-weight-bold" style="
        background: linear-gradient(45deg,rgb(60, 105, 0),rgb(0, 81, 148)); /* Gradasi hijau ke biru */
        -webkit-background-clip: text; /* Clip background pada teks */
        -webkit-text-fill-color: transparent; /* Jadikan teks transparan agar gradasi terlihat */
        font-size: 2em; /* Ukuran font */
        display: inline-block; /* Agar padding sesuai */
    ">
                Data Pelanggan
            </h3>
        </div>

        <div class="col-md-3 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    Ekspor
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('pelanggan.export', [
                        'format' => 'pdf',
                        'tgl_tagih_plg' => request('tgl_tagih_plg'),
                        'paket_plg' => request('paket_plg'),
                        'harga_paket' => request('harga_paket'),
                        'status_pembayaran' => request('status_pembayaran'),
                    ]) }}" class="dropdown-item">PDF</a>

                    <a href="{{ route('pelanggan.export', [
                        'format' => 'excel',
                        'tgl_tagih_plg' => request('tgl_tagih_plg'),
                        'paket_plg' => request('paket_plg'),
                        'harga_paket' => request('harga_paket'),
                        'status_pembayaran' => request('status_pembayaran'),
                    ]) }}" class="dropdown-item">Excel</a>
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
                        <option value="{{ $i }}" {{ request('tgl_tagih_plg') == $i ? 'selected' : '' }}>
                        {{ $i }}
                        </option>
                        @endfor
                </select>
                <select name="paket_plg" id="paket_plg">
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

                <select name="harga_paket" id="harga_paket">
                    <option value="">Harga</option>
                    <option value="50000" {{ request('jumlah_pembayaran') == '50000' ? 'selected' : '' }}>
                        {{ number_format(50000, 0, ',', '.') }}
                    </option>
                    <option value="75000" {{ request('jumlah_pembayaran') == '75000' ? 'selected' : '' }}>
                        {{ number_format(75000, 0, ',', '.') }}
                    </option>
                    <option value="100000" {{ request('jumlah_pembayaran') == '100000' ? 'selected' : '' }}>
                        {{ number_format(100000, 0, ',', '.') }}
                    </option>
                    <option value="105000" {{ request('jumlah_pembayaran') == '105000' ? 'selected' : '' }}>
                        {{ number_format(105000, 0, ',', '.') }}
                    </option>
                    <option value="115000" {{ request('jumlah_pembayaran') == '115000' ? 'selected' : '' }}>
                        {{ number_format(115000, 0, ',', '.') }}
                    </option>

                    <option value="120000" {{ request('jumlah_pembayaran') == '120000' ? 'selected' : '' }}>
                        {{ number_format(120000, 0, ',', '.') }}
                    </option>
                    <option value="125000" {{ request('jumlah_pembayaran') == '125000' ? 'selected' : '' }}>
                        {{ number_format(125000, 0, ',', '.') }}
                    </option>
                    <option value="150000" {{ request('jumlah_pembayaran') == '150000' ? 'selected' : '' }}>
                        {{ number_format(150000, 0, ',', '.') }}
                    </option>
                    <option value="165000" {{ request('jumlah_pembayaran') == '165000' ? 'selected' : '' }}>
                        {{ number_format(165000, 0, ',', '.') }}
                    </option>
                    <option value="175000" {{ request('jumlah_pembayaran') == '175000' ? 'selected' : '' }}>
                        {{ number_format(175000, 0, ',', '.') }}
                    </option>
                    <option value="205000" {{ request('jumlah_pembayaran') == '205000' ? 'selected' : '' }}>
                        {{ number_format(205000, 0, ',', '.') }}
                    </option>
                    <option value="250000" {{ request('jumlah_pembayaran') == '250000' ? 'selected' : '' }}>
                        {{ number_format(250000, 0, ',', '.') }}
                    </option>
                    <option value="265000" {{ request('jumlah_pembayaran') == '265000' ? 'selected' : '' }}>
                        {{ number_format(265000, 0, ',', '.') }}
                    </option>
                    <option value="305000" {{ request('jumlah_pembayaran') == '305000' ? 'selected' : '' }}>
                        {{ number_format(305000, 0, ',', '.') }}
                    </option>
                    <option value="750000" {{ request('jumlah_pembayaran') == '750000' ? 'selected' : '' }}>
                        {{ number_format(750000, 0, ',', '.') }}
                    </option>
                    <option value="vcr" {{ request('jumlah_pembayaran') == 'vcr' ? 'selected' : '' }}>
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


        <table class="table table-bordered table-responsive"
            style="color: black; width: 100%; font-size: 0.85em; table-layout: fixed;">
            <thead class="custom-cell danger" style="color: white;">
                <tr class="font-weight-bold">
                    <th style="width: 1%; padding: 1px;">No</th>
                    <th style="width: 1%; padding: 1px;">ID</th>
                    <th style="width: 1%; padding: 1px;">Nama</th>
                    <th style="width: 1%; padding: 1px;">Alamat</th>
                    <th style="width: 1%; padding: 1px;">No Telpon</th>
                    <th style="width: 1%; padding: 1px;">Aktivasi</th>
                    <th style="width: 1%; padding: 1px;">Paket</th>
                    <th style="width: 1%; padding: 1px;">Harga</th>
                    <th style="width: 1%; padding: 0; margin: 0; text-align: center;">Tanggal Tagih</th>
                    <th style="width: 1%; padding: 1px;">Keterangan</th>
                    <th style="width: 1%; padding: 1px;">Bayar Terakhir</th>
                    <th style="width: 1%; padding: 1px;">Status Pembayaran</th>

                </tr>
            </thead>
            <tbody>
                @forelse ($pelanggan as $no => $item)
                <tr class="">
                    <td style="padding: 1px;">
                        <a href="{{ route('pelanggan.detail', $item->id) }}"
                            style="text-decoration: none; color: inherit;">
                            {{ ($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration }}
                        </a>
                    </td>

                    <!-- ID Pelanggan -->
                    <td style="padding: 1px;">
                        <a href="{{ route('pelanggan.detail', $item->id) }}"
                            style="text-decoration: none; color: inherit;">
                            {{ $item->id_plg }}
                        </a>
                    </td>

                    <!-- Nama Pelanggan -->
                    <td style="padding: 1px;">
                        <a href="{{ route('pelanggan.detail', $item->id) }}"
                            style="text-decoration: none; color: inherit;">
                            {{ $item->nama_plg }}
                        </a>
                    </td>

                    <td style="padding: 1px;">
                        <a href="{{ route('pelanggan.detail', $item->id) }}"
                            style="text-decoration: none; color: inherit;">
                            {{ $item->alamat_plg }}
                        </a>
                    </td>

                    <td style="padding: 1px;">
                        <a href="{{ route('pelanggan.detail', $item->id) }}"
                            style="text-decoration: none; color: inherit;">
                            {{ $item->no_telepon_plg }}
                        </a>
                    </td>
                    <td style="padding: 1px;">
                        <a href="{{ route('pelanggan.detail', $item->id) }}"
                            style="text-decoration: none; color: inherit;">
                            {{ $item->aktivasi_plg }}
                        </a>
                    </td>
                    <td style="padding: 1px;">
                        <a href="{{ route('pelanggan.detail', $item->id) }}"
                            style="text-decoration: none; color: inherit;">
                            {{ $item->paket_plg }}
                        </a>
                    </td>
                    <td style="padding: 1px;">
                        <a href="{{ route('pelanggan.detail', $item->id) }}"
                            style="text-decoration: none; color: inherit;">
                            {{ number_format($item->harga_paket, 0, ',', '.') }}
                        </a>
                    </td>
                    <td style="width: 1%; padding: 0; margin: 0; text-align: center;">
                        <a href="{{ route('pelanggan.detail', $item->id) }}"
                            style="text-decoration: none; color: inherit;">
                            {{ $item->tgl_tagih_plg }}
                        </a>
                    </td>
                    <td style="padding: 1px;">
                        <a href="{{ route('pelanggan.detail', $item->id) }}"
                            style="text-decoration: none; color: inherit;">
                            {{ $item->keterangan_plg }}
                        </a>
                    </td>

                    <td style="padding: 1px;">
                        <a href="{{ route('pelanggan.detail', $item->id) }}"
                            style="text-decoration: none; color: inherit;">
                            {{ optional($item->pembayaranTerakhir)->tanggal_pembayaran
                            ? \Carbon\Carbon::parse($item->pembayaranTerakhir->tanggal_pembayaran)->locale('id')->isoFormat('MMMM Y')
                            : '-' }}
                        </a>
                    </td>

                    <!---
                                <td style="padding: 1px;">
                                    <span class="badge {{ strcasecmp($item->status_pembayaran, 'Sudah Bayar') === 0 ? 'bg-success' : 'bg-danger' }} text-white">
                                        {{ $item->status_pembayaran }}
                                    </span>
                                </td>
                                    -->
                    <td class="row" style="padding: 2px; font-size: 0.8em; height: 10px;">

                        <select name="tanggal_pembayaran" class="form-control ml-4" onchange="this.form.submit()"
                            style="width: 16%;  padding: 2px; height: 25px;">
                            <option value="">Riwayat Pembayaran</option>
                            @foreach ($item->pembayaran as $pembayaran)
                            @php
                            $tanggalPembayaran = \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran);
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
                            style="font-size: 0.75em; height: 20px; line-height: 20px;">{{ $item->status_pembayaran }}</span>
                    </td>




                    <!--  <td style="padding: 0; margin: 0; text-align: center;">
                        <a href="{{ route('pelanggan.detail', $item->id) }}" class="btn btn-warning btn-xs" style="padding: 2px 5px; font-size: 0.75em;">Detail</a>
                    </td> -->

                </tr>
                @empty
                <tr>
                    <td colspan="14" class="text-center" style="padding: 10px;">Tidak ada data ditemukan</td>
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
