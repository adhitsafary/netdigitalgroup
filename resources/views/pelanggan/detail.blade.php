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
                        <h5 style="color: black" class="fotn font-weight-bold">Informasi Pelanggan</h5>
                        <ul class="list-group font-weight-bold" style="color: black">
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
                        <h5 style="color: black" class="font-weight-bold">Detail Paket</h5>
                        <ul class="list-group font-weight-bold"  style="color: black">
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
                        <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" class="">
                            <img src="{{asset('asset/img/icon/edit.png') }}" style="height : 40px; width : 40px" alt="">
                        </a>
                        <form action="{{ route('pelanggan.destroy', $pelanggan->id) }}" method="POST"
                            class="d-inline-block">
                            @csrf
                            <a href="#" onclick="if(confirm('Yakin ingin menghapus data ini?')) { this.closest('form').submit(); return false; }" style="display: inline-block;">
                                <img src="{{ asset('asset/img/icon/delete.png') }}" style="height: 35px; width: 35px;" alt="Hapus" >
                            </a>
                        </form>

                    <!--    <a href="#" class=""
                            onclick="showBayarModal({{ $pelanggan->id }}, '{{ $pelanggan->nama_plg }}', {{ $pelanggan->harga_paket }})">
                        <img src="{{asset('asset/img/icon/bayar.png')}}" style="heigth : 35px; width : 35px; " alt="">
                        </a>

                        <div class="modal fade" id="bayarModal" tabindex="-1" aria-labelledby="bayarModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="bayarModalLabel">Pembayaran</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                  
                                    <form id="bayarForm" method="POST">
                                        @csrf
                                        <input type="hidden" name="id" id="pelangganId">
                                        <div class="modal-body">
                                         
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
                                                    <option value="CASH">Cash</option>
                                                    <option value="TF">Transfer</option>
                                                </select>
                                            </div>

                          
                                            <div class="mb-3">
                                                <p id="pembayaranDetails"></p>
                                            </div>
                                        </div>

                                    
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary">Bayar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>  -->
                        <a href="{{ route('pelanggan.historypembayaran', $pelanggan->id) }}"
                            class="btn btn-info btn-sm">
                        <img src="{{asset('asset/img/icon/riwayat.png')}}" style="height : 35px; width : 35px; " alt="">
                        </a>

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
