@extends($layout)

@section('konten')
    <div class="  pl-5 pr-5 mb-4">
        <!-- Form Filter dan Pencarian -->
        <div class="row mb-2 align-items-center">
            <div class="col-md-3">
                <!-- Form Pencarian -->
                <form action="{{ route('pelangganof.index') }}" method="GET" class="form-inline">
                    <!-- Input Pencarian -->
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control"
                            value="{{ request('search') }}" placeholder="Pencarian">
                    </div>
                    <!-- Tombol Cari -->
                    <button type="submit" name="action" value="search" class="btn btn-danger ml-2">Cari</button>
                </form>
            </div>

            <div class="col-md-6 text-center">

                <a href="/pelanggan" class="btn btn-primary btn-lg mt-2"
                    style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                    Data Pelanggan</a>
                <div class="btn btn-primary btn-lg mt-2" data-toggle="modal" data-target="#filterModal"
                    style="cursor: default; background: linear-gradient(45deg, #ff0000, #ff8c00); color: #ffffff;">
                    Data Pelanggan OFF
                </div>
                <a href="/isolir" class="btn btn-primary btn-lg mt-2"
                    style="cursor: default; background: linear-gradient(45deg, #007bff, #00b4db); color: #ffffff;">
                    Data Pelanggan Isolir</a>
                <!-- Modal -->
                <div class="modal fade" id="filterModal" tabindex="-1" role="dialog" aria-labelledby="filterModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="filterModalLabel">Filter Pelanggan Off</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form class="filterForm" method="GET" action="{{ route('pelangganof.filterTagihindex') }}">
                                    <div class="form-group">
                                        <label for="paket_plg">Paket</label>
                                        <select name="paket_plg" id="paket_plg">
                                            <option value="">Pilih Paket</option>
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
                                    <div class="form-group">
                                        <label for="tgl_tagih_plg">Tanggal Tagih</label>
                                        <select name="tgl_tagih_plg" id="tgl_tagih_plg">
                                            <option value="">Pilih Tanggal Tagih</option>
                                            @for ($i = 1; $i <= 32; $i++)
                                                <option value="{{ $i }}"
                                                    {{ request('tgl_tagih_plg') == $i ? 'selected' : '' }}>
                                                    {{ $i }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="button" class="btn btn-primary"
                                    onclick="document.querySelector('.filterForm').submit();">Terapkan Filter</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


        </div>
        <table class="table table-responsive table-bordered  " style="color: black;">
            <thead class="table table-primary " style="color: black;">
                <tr>
                    <th>No</th>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No Telpon</th>
                    <th>Aktivasi</th>
                    <th>Paket</th>
                    <th>Harga Paket</th>
                    <th>Tanggal Tagih</th>
                    <th>ODP</th>
                    <th>Longitude</th>
                    <th>Atitude</th>
                    <th>Keterangan</th>
                    <th>Tanggal Off</th>
                    <th>Status Pembayaran</th>
                    <th>Detail</th>


                </tr>
            </thead>
            <tbody>
                @forelse ($pelangganof as $no => $item)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $item->id_plg }}</td>
                        <td>{{ $item->nama_plg }}</td>
                        <td>{{ $item->alamat_plg }}</td>
                        <td>{{ $item->no_telepon_plg }}</td>
                        <td>{{ $item->aktivasi_plg }}</td>
                        <td>{{ $item->paket_plg }}</td>
                        <td>{{ number_format($item->harga_paket, 0, ',', '.') }}</td>
                        <td>{{ $item->tgl_tagih_plg }}</td>
                        <td>{{ $item->odp }}</td>
                        <td>{{ $item->longitude }}</td>
                        <td>{{ $item->latitude }}</td>
                        <td>{{ $item->keterangan_plg }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->status_pembayaran }}</td>

                        <td>
                            <a href="{{ route('pelangganof.detail', $item->id) }}" class="btn btn-warning btn-sm">Detail
                            </a>
                        </td>



                        <script>
                            function validateForm(form) {
                                let requiredFields = ['id_plg', 'nama_plg', 'alamat_plg', 'no_telepon_plg', 'paket_plg', 'odp', 'maps'];
                                for (let field of requiredFields) {
                                    if (!form[field].value) {
                                        alert('Form ' + field + ' tidak boleh kosong!');
                                        return false;
                                    }
                                }
                                return true;
                            }
                        </script>

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
