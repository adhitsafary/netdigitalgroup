@extends($layout)

@section('konten')
    <div class="  pl-5 pr-5 mb-4">
        <!-- Form Filter dan Pencarian -->
        <form action="{{ route('rekap_pemasangan.index') }}" method="GET" class="form-inline mb-4">
            <div class="input-group mr-2">
                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}"
                    placeholder="Pencarian">

            </div>
            <div class="input-group mr-2">
                <input type="date" name="created_at_dari" id="created_at_dari" class="form-control"
                    value="{{ request('created_at_dari') }}" placeholder="Dari Tanggal">
            </div>
            <div class="input-group mr-2">
                <input type="date" name="created_at_sampai" id="created_at_sampai" class="form-control"
                    value="{{ request('created_at_sampai') }}" placeholder="Sampai Tanggal">
            </div>
            <button type="submit" class="btn btn-primary">Filter</button>
        </form>

        <a href="/rekap_pemasangan/create" class="btn btn-success">Buat Rekap pemasangan</a>

        <div style="display: flex; justify-content: center;" class="mb-3">

            <h4 style="color: black;" class="font font-weight-bold">Data Rekap pemasangan</h4>
        </div>

        <th class="mt-2">
            <form action="{{ route('rekap_pemasangan.index') }}" method="GET">
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


                <button type="submit" class="btn btn-primary ">Filter</button>
            </form>
        </th>


        <table class="table table-bordered  table-responsive" style="color: black;">
            <thead class="table table-primary " style="color: black;">
                <tr>
                    <th>No</th>
                    <th>Identitas</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>No Telepon</th>
                    <th>Tanggal Aktifasi</th>
                    <th>Paket</th>
                    <th>Nominal</th>
                    <th>Jatuh Tempo</th>
                    <th>Status</th>
                    <th>Tanggal Pengajuan</th>
                    <th>Registrasi</th>
                    <th>Marketing</th>
                    <th>Aktivasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rekap_pemasangan as $no => $item)
                    <tr class="font-weight-bold">
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $item->nik }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->alamat }}</td>

                        <td>{{ $item->no_telpon }}</td>
                        <td>{{ $item->tgl_aktivasi }}</td>
                        <td>{{ $item->paket_plg }}</td>

                        <td>{{ $item->harga_paket }}</td>
                        <td>{{ $item->jt }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->tgl_pengajuan }}</td>
                        <td>{{ $item->registrasi }}</td>
                        <td>{{ $item->marketing }}</td>
                        <td>
                            @php
                                // Cek apakah pelanggan sudah diaktivasi
                                $isActivated = \App\Models\Pelanggan::where('id_plg', $item->id_plg)->exists();
                            @endphp

                            @if ($isActivated)
                                <!-- Jika sudah diaktivasi, tampilkan ikon ceklis tidak bisa diklik -->
                                <img src="{{ asset('asset/img/ceklis2.png') }}" alt="Sudah Aktivasi"
                                    style="width:45px; height:45px;">
                            @else
                                <!-- Jika belum diaktivasi, tampilkan gambar x dan tombol ceklis untuk aktivasi -->
                                <a href="{{ route('rekap_pemasangan.aktivasi', $item->id) }}"
                                    onclick="return confirm('Apakah Pelanggan Sudah Selesai Pasang?')">
                                    <img src="{{ asset('asset/img/x.png') }}" alt="Belum Aktivasi"
                                        style="width:40px; height:40px;">
                                </a>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('rekap_pemasangan.edit', $item->id) }}"
                                class="btn btn-warning btn-sm">Edit</a>

                            <form action="{{ route('rekap_pemasangan.destroy', $item->id) }}" method="POST"
                                class="d-inline-block">
                                @csrf
                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </td>

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
