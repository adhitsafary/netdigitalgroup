@extends($layout)

@section('konten')
<div class="container">
    <h4 class="mb-4">Bayar Tagihan Pelanggan</h4>


    <!-- Form Filter dan Pencarian -->
    <div class="row align-items-center">
        <table class="table table-bordered mt-2">
            <thead class="custom-cell head">
                <tr>
                    <th>Total semua Pembayaran hari ini</th>
                    <th>Total Tagihan Hari Ini</th>
                    <th>Tertagih</th>
                    <th>Sisa Tagihan</th>
                    <th>Filter Pembayaran</th>



                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="custom-cell info">
                        Rp {{ number_format($total_user_bayar, 0, ',', '.') }} User: {{ $total_jml_user }}
                    </td>

                    <td class="custom-cell warning">
                        <a style="color: black" ; href="{{ route('pelanggan.redirect') }}"> Rp
                            Rp {{ number_format($totalTagihanHariIni, 0, ',', '.') }} User:
                            {{ $jumlahPelangganMembayarHariIni }} </a>
                    </td>

                    <td class="custom-cell success">
                        <a href="{{ route('pelanggan.sudahbayar') }}"> Rp
                            {{ number_format($totalPendapatanharian_semua, 0, ',', '.') }} User:
                            {{ $totalUserHarian_semua }}</a>
                    </td>

                    <td class="custom-cell danger">
                        <a href="{{ route('pelanggan.belumbayar') }}"> Rp
                            {{ number_format($totalTagihanTertagih, 0, ',', '.') }} User: {{ $totalUserTertagih }} </a>
                    </td>
                    <td class="custom-cell danger">
                        <a href="#"> Rp
                 {{ number_format($totaljumlahpembayaranUntuk_filter, 0, ',', '.') }} User:
                            {{ $totalPelangganUntuk_filter}} </a>
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
    <!-- End Form Filter dan Pencarian -->



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

    

    <form action="{{ route('pembayaran_mudah.index') }}" method="GET">
        <div class="form-group d-flex">
            <input type="text" name="q" class="form-control me-2 " placeholder="Cari berdasarkan ID atau Nama"
                value="{{ $query ?? '' }}">

            <select name="untuk_pembayaran" id="untuk_pembayaran" class="form-select me-2 ml-2  mr-2">
                <option value="">Pilih Pembayaran</option>
                <option value="piutang" {{ request('untuk_pembayaran') == 'piutang' ? 'selected' : '' }}>Piutang</option>
                <option value="tagihan" {{ request('untuk_pembayaran') == 'tagihan' ? 'selected' : '' }}>Tagihan</option>
                <option value="psb" {{ request('untuk_pembayaran') == 'psb' ? 'selected' : '' }}>PSB</option>
            </select>
            <button type="submit" class="btn btn-primary w-50">Cari / Refresh</button>

        </div>
    </form>




    <div class="mt-4">
        @if (!$query_cari)
            <p class="text-muted">Silakan masukkan ID atau Nama untuk mencari data pelanggan.</p>

            <div>
                <!-- Tabel Pembayaran -->
                <table class="table table-bordered table-responsive" style="color: black;">
                    <thead class="table table-primary " style="color: black;">
                        <tr>
                            <th style="width: 1%; padding: 1px;">No</th>
                            <th style="width: 1%; padding: 1px;">Nama Pelanggan</th>
                            <th style="width: 1%; padding: 1px;">Alamat</th>
                            <th style="width: 1%; padding: 1px;">Tanggal Tagih </th>
                            <th style="width: 1%; padding: 1px;">Paket</th>
                            <th style="width: 1%; padding: 1px;">Harga</th>
                            <th style="width: 1%; padding: 1px;">Metode Pembayaran</th>
                            <th style="width: 1%; padding: 1px;">Tanggal Pembayaran</th>
                            <th style="width: 1%; padding: 1px;">Keterangan</th>
                            <th style="width: 1%; padding: 1px;">Admin</th>
                            <th style="width: 1%; padding: 1px;">Hapus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pembayaran as $no => $item)
                            <tr class="">
                                <td style="padding: 1px;">
                                    {{ ($pembayaran->currentPage() - 1) * $pembayaran->perPage() + $loop->iteration }}</td>
                                <td style="padding: 1px;">{{ $item->nama_plg }}</td>
                                <td style="padding: 1px;">{{ $item->alamat_plg }}</td>
                                <td style="padding: 1px;">{{ $item->tgl_tagih_plg }}</td>
                                <td style="padding: 1px;">{{ $item->paket_plg }}</td>
                                <td style="padding: 1px;">{{ number_format($item->jumlah_pembayaran, 0, ',', '.') }}</td>
                                <td style="padding: 1px;">{{ $item->metode_transaksi }}</td>
                                <td style="padding: 1px;">{{ $item->created_at }}</td>
                                <td style="padding: 1px;">{{ $item->untuk_pembayaran }}</td>
                                <td style="padding: 1px;">{{ $item->admin_name }}</td>
                                <td style="padding: 1px;">
                                    <form action="{{ route('pembayaran.destroy', $item->id) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf
                                        <a href="#"
                                            onclick="if(confirm('Yakin ingin menghapus data ini?')) { this.closest('form').submit(); return false; }"
                                            style="display: inline-block;" class="btn btn-danger btn-sm">
                                            <img src="{{ asset('asset/img/icon/delete.png') }}"
                                                style="height: 25px; width: 25px;" alt="Hapus">
                                        </a>
                                    </form>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data pembayaran ditemukan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>
        @elseif($pelanggan->isEmpty())
            <p class="text-muted">Tidak ditemukan hasil untuk "{{ $query_cari }}"</p>
        @else

            <table class="table table-bordered table-responsive"
                style="color: black; width: 100%; font-size: 0.9em; table-layout: fixed;">
                <thead class="table table-primary" style="color: black;">
                    <tr>
                        <th style="width: 1%; padding: 1px;">No</th>
                        <th style="width: 1%; padding: 1px;">ID Pelanggan</th>
                        <th style="width: 1%; padding: 1px;">Nama</th>
                        <th style="width: 1%; padding: 1px;">Alamat</th>
                        <th style="width: 1%; padding: 1px;">Harga</th>
                        <th style="width: 1%; padding: 1px;">Tanggal Tagih</th>
                        <th style="width: 1%; padding: 1px;">Status Pembayaran</th>
                        <th style="width: 1%; padding: 1px;">Bayar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pelanggan as $no => $item)
                                <tr>
                                    <td style="padding: 1px;">
                                        {{ ($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration }}</td>
                                    <td style="padding: 1px;">{{ $item->id_plg }}</td>
                                    <td style="padding: 1px;">{{ $item->nama_plg }}</td>
                                    <td style="padding: 1px;">{{ $item->alamat_plg }}</td>
                                    <td style="padding: 1px;">{{ number_format($item->harga_paket, 0, ',', '.') }}</td>
                                    <td style="padding: 1px;">{{ $item->tgl_tagih_plg }}</td>
                                    <td style="padding: 1px;">
                                        {{ optional($item->pembayaranTerakhir)->tanggal_pembayaran
                        ? \Carbon\Carbon::parse($item->pembayaranTerakhir->tanggal_pembayaran)->locale('id')->isoFormat('MMMM Y')
                        : '-' }}
                                    </td>
                                    <td style="padding: 0; margin: 0; text-align: center;">
                                        <a href="#" class="btn btn-success btn-xs" style="padding: 2px 5px; font-size: 0.75em;"
                                            onclick="showBayarModal({{ $item->id }}, '{{ $item->nama_plg }}', {{ $item->harga_paket }})"><img
                                                src="{{asset('asset/img/icon/bayar.png')}}" style="height : 30px; width : 30px; "
                                                alt=""></a>
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
                                                            <label for="tanggal_pembayaran" class="form-label">Untuk Pembayaran
                                                                <label for="tanggal_pembayaran" class="form-label">Untuk Pembayaran
                                                                    Bulan</label>
                                                                <input type="month" class="form-select" id="tanggal_pembayaran"
                                                                    name="tanggal_pembayaran" placeholder="Pilih bulan">
                                                        </div>



                                                        <div class="mb-3">
                                                            <label for="metodeTransaksi" class="form-label">Metode
                                                                Transaksi</label>
                                                            <select class="form-select" id="metodeTransaksi" name="metode_transaksi"
                                                                required>
                                                                <option value="">Pilih metode</option>
                                                                <option value="TF">TF</option>
                                                                <option value="CASH">KANTOR</option>

                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="untuk_pembayaran" class="form-label">Status
                                                                Pembayaran</label>
                                                            <select class="form-select" id="untuk_pembayaran"
                                                                name="untuk_pembayaran" required>
                                                                <option value="">Pilih Pembayaran</option>
                                                                <option value="tagihan">Tagihan </option>
                                                                <option value="piutang">Piutang </option>
                                                                <option value="PSB">PSB </option>

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
                    @endforeach
                </tbody>
            </table>


        @endif
        </div>
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