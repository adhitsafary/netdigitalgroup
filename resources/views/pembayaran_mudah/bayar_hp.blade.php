@extends($layout)

@section('konten')
<div class="container">
    <h4 class="mb-4 text-center">Bayar Tagihan Pelanggan</h4>

    <!-- Informasi Total -->
    <div class="row g-3 ">


        <div class="col-6 col-md-3 card">
            <div class="bg-info text-white p-2 font-weight-bold ">
                <p class="card-title">Total Pembayaran</p>
                <a href="#" class="card-text"> Rp {{ number_format($total_user_bayar, 0, ',', '.') }} </a>
                <p>User: {{ $total_jml_user }}</p>
            </div>
        </div>
        <div class="col-6 col-md-3 card">
            <div class="bg-warning text-dark p-2 font-weight-bold">
                <p class="card-title">Tagihan Hari Ini</p>
                <a href="{{ route('pelanggan.redirect') }}" class="card-text"> Rp {{ number_format($totalTagihanHariIni, 0, ',', '.') }} </a>
                <p>User: {{ $jumlahPelangganMembayarHariIni }}</p>
            </div>
        </div>
        <div class="col-6 col-md-3 card">
            <div class="bg-success text-white p-2 font-weight-bold">
                <p class="card-title">Tertagih</p>
                <a href="{{ route('pelanggan.sudahbayar') }}" class="card-text">Rp {{ number_format($totalPendapatanharian_semua, 0, ',', '.') }}</a>
                <p>User: {{ $totalUserHarian_semua }}</p>
            </div>
        </div>
        <div class="col-6 col-md-3 card">
            <div class="bg-danger text-white p-2 font-weight-bold">
                <p class="card-title">Sisa Tagihan</p>
                <a href="{{ route('pelanggan.belumbayar') }}" class="card-text">Rp {{ number_format($totalTagihanTertagih, 0, ',', '.') }} </a>
                <p>User: {{ $totalUserTertagih }}</p>
            </div>
        </div>
    </div>


    <!-- Form Pencarian -->
    <form action="{{ route('pembayaran_mudah.bayar_hp') }}" method="GET" class="mt-4">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Cari berdasarkan ID atau Nama"
                value="{{ $query ?? '' }}">

            <button type="submit" class="btn btn-primary">Cari</button>
        </div>
    </form>


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

    <!-- Tabel Pembayaran -->
    <div class="mt-4">
        @if (!$query_cari)
        <p class="text-muted">Silakan masukkan ID atau Nama untuk mencari data pelanggan.</p>

        <div>
            <!-- Tabel Pembayaran -->
            <div class="container mt-4">
                <div class="row">
                    @forelse ($pembayaran as $no => $item)
                    <div class="col-12 col-md-6 col-lg-4 mb-3">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    Pelanggan
                                    {{ ($pembayaran->currentPage() - 1) * $pembayaran->perPage() + $loop->iteration }}
                                </h5>
                                <p class="card-text">
                                    <strong>Nama:</strong> {{ $item->nama_plg }}<br>
                                    <strong>Alamat:</strong> {{ $item->alamat_plg }}<br>
                                    <strong>Tanggal Tagih:</strong> {{ $item->tgl_tagih_plg }}<br>
                                    <strong>Harga:</strong> Rp
                                    {{ number_format($item->jumlah_pembayaran, 0, ',', '.') }}<br>
                                    <strong>Metode Pembayaran:</strong> {{ $item->metode_transaksi }}<br>
                                    <strong>Tanggal Pembayaran:</strong> {{ $item->created_at }}<br>
                                    <strong>Keterangan:</strong> {{ $item->untuk_pembayaran }}<br>
                                    <strong>Admin:</strong> {{ $item->admin_name }}
                                </p>
                                <div class="d-flex justify-content-end">

                                    <form action="{{ route('pembayaran.destroy', $item->id) }}" method="POST"
                                        class="d-inline-block">
                                        @csrf
                                        <a href="#"
                                            onclick="if(confirm('Yakin ingin menghapus data ini?')) { this.closest('form').submit(); return false; }"
                                            style="display: inline-block;">
                                            <img src="{{ asset('asset/img/icon/delete.png') }}"
                                                style="height: 35px; width: 35px;" alt="Hapus">
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-12 text-center">
                        <p>Tidak ada data pembayaran ditemukan</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
        <div>
            @elseif($pelanggan->isEmpty())
            <p class="text-muted">Tidak ditemukan hasil untuk "{{ $query_cari }}"</p>
            @else

            <div class="row">
                @foreach ($pelanggan as $no => $item)
                <div class="col-12 col-md-6 col-lg-4 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Pelanggan
                                {{ ($pelanggan->currentPage() - 1) * $pelanggan->perPage() + $loop->iteration }}
                            </h5>
                            <p class="card-text">
                                <strong>ID Pelanggan:</strong> {{ $item->id_plg }}<br>
                                <strong>Nama:</strong> {{ $item->nama_plg }}<br>
                                <strong>Alamat:</strong> {{ $item->alamat_plg }}<br>
                                <strong>Harga:</strong> Rp {{ number_format($item->harga_paket, 0, ',', '.') }}<br>
                                <strong>Tanggal Tagih:</strong> {{ $item->tgl_tagih_plg }}<br>
                                <strong>Status Pembayaran:</strong>
                                {{ optional($item->pembayaranTerakhir)->tanggal_pembayaran
                    ? \Carbon\Carbon::parse($item->pembayaranTerakhir->tanggal_pembayaran)->locale('id')->isoFormat('MMMM Y')
                    : '-' }}
                            </p>
                            <td style="padding: 0; margin: 0; text-align: center;">
                                <a href="#" class="btn btn-success btn-xs" style="padding: 2px 5px; font-size: 0.75em;"
                                    onclick="showBayarModal({{ $item->id }}, '{{ $item->nama_plg }}', {{ $item->harga_paket }})">
                                    <img
                                        src="{{asset('asset/img/icon/bayar.png')}}"
                                        style="height : 30px; width : 30px; " alt=""></a>
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
                                                        <label for="tanggal_pembayaran" class="form-label">Untuk
                                                            Pembayaran
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
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
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
            form.action = `/pelanggan/${id}/bayar_mudah_hp`; // Set action URL with the ID

            var bayarModal = new bootstrap.Modal(document.getElementById('bayarModal'));
            bayarModal.show();
        }
    </script>
