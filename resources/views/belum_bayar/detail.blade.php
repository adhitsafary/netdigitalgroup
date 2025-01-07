@extends($layout)

@section('konten')
    <div class="container mt-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Detail Pelanggan</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Informasi Pelanggan</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>ID:</strong> {{ $pelanggan->id_plg }}
                            </li>
                            <li class="list-group-item">
                                <strong>Nama:</strong> {{ $pelanggan->nama_plg }}
                            </li>
                            <li class="list-group-item">
                                <strong>Alamat:</strong> {{ $pelanggan->alamat_plg }}
                            </li>
                            <li class="list-group-item">
                                <strong>No Telepon:</strong> {{ $pelanggan->no_telepon_plg }}
                            </li>
                            <li class="list-group-item">
                                <strong>Aktivasi:</strong> {{ $pelanggan->aktivasi_plg }}
                            </li>
                            <li class="list-group-item">
                                <strong>Longitude :</strong> {{ $pelanggan->longitude }}
                            </li>


                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Detail Paket</h5>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <strong>Paket:</strong> {{ $pelanggan->paket_plg }}
                            </li>
                            <li class="list-group-item">
                                <strong>Harga Paket:</strong> {{ number_format($pelanggan->harga_paket, 0, ',', '.') }}

                            </li>
                            <li class="list-group-item">
                                <strong>Tanggal Tagih : </strong>
                               {{ $pelanggan->tgl_tagih_plg }}
                            </li>

                            <li class="list-group-item">
                                <strong>Keterangan :</strong> {{ $pelanggan->keterangan_plg }}
                            </li>
                            <li class="list-group-item">
                                <strong>ODP :</strong> {{ $pelanggan->odp }}
                            </li>
                            <li class="list-group-item">
                                <strong>Latitude:</strong> {{ $pelanggan->latitude}}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <div class="d-flex justify-content-between">
                    <div>
                        <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('pelanggan.destroy', $pelanggan->id) }}" method="POST"
                            class="d-inline-block">
                            @csrf

                            <button class="btn btn-danger btn-sm"
                                onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                        </form>
                        <a href="{{ route('pelanggan.off', $pelanggan->id) }}" class="btn btn-danger btn-sm"
                            onclick="return confirm('Pelanggan Atas Nama {{ $pelanggan->nama_plg }} Akan di Non Aktifkan?')">Pelanggan
                            Off</a>
                        <a href="#" class="btn btn-success btn-sm"
                            onclick="showBayarModal({{ $pelanggan->id }}, '{{ $pelanggan->nama_plg }}', {{ $pelanggan->harga_paket }})">Bayar</a>

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
                                                <label for="tanggalPembayaran" class="form-label">Tanggal
                                                    Pembayaran</label>
                                                <input type="date" class="form-control" id="tanggalPembayaran"
                                                    name="tanggal_pembayaran" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="metodeTransaksi" class="form-label">Metode Transaksi</label>
                                                <select class="form-select" id="metodeTransaksi" name="metode_transaksi"
                                                    required>
                                                    <option value="">Pilih metode</option>
                                                    <option value="Cash Kantor">Cash Kantor</option>
                                                    <option value="Cash Pickup">Cash Pickup</option>
                                                    <option value="Transfer Bca">Transfer Via BCA</option>
                                                    <option value="Transfer Dana">Transfer Via Dana</option>
                                                </select>
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
                        <a href="{{ route('pelanggan.historypembayaran', $pelanggan->id) }}"
                            class="btn btn-info btn-sm">Riwayat Pembayaran</a>

                    </div>
                    <div>
                        <a href="{{ route('pelanggan.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
                    </div>
                </div>
            </div>
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
