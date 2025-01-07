@extends($layout)

@section('konten')
    <div class=" p-5 mt-4">
        <div class="row mb-4">
            <div class="col-md-9">
                <form method="GET" action="{{ route('pembayaran.filter') }}" class="form-inline">
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control"
                            value="{{ request('search') }}" placeholder="Pencarian">
                        <div class="form-group">
                            <input type="date" name="date_start" id="date_start" class="form-control"
                                value="{{ $date_start }}">
                        </div>
                        <div class="form-group">
                            <input type="date" name="date_end" id="date_end" class="form-control"
                                value="{{ $date_end }}">
                        </div>
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3 text-right">
                <div class="btn-group">
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        Ekspor
                    </button>
                    <div class="dropdown-menu">
                        <a href="{{ route('pembayaran.export', ['format' => 'pdf', 'date_start' => request('date_start'), 'date_end' => request('date_end')]) }}"
                            class="dropdown-item">PDF</a>
                        <a href="{{ route('pembayaran.export', ['format' => 'excel', 'date_start' => request('date_start'), 'date_end' => request('date_end')]) }}"
                            class="dropdown-item">Excel</a>
                    </div>
                </div>
            </div>
        </div>
        <div style="color: black" class="h6 font-weight-bold">
            <strong>Total Jumlah Pembayaran : </strong> {{ number_format($totalJumlahPembayaran) }}
            <strong class="mr-5"></strong>
            <strong>Total Jumlah Pelanggan Bayar : </strong> {{ $totalPelanggan }}
        </div>

        <div style="display: flex; justify-content: center;" class="mb-2">

            <h5 style="color: black;" class="font font-weight-bold">Data Pembayaran</h5>
        </div>

        <!-- Tabel Pembayaran -->
        <table class="table table-bordered table-responsive" style="color: black;">
            <thead class="table table-primary " style="color: black;">
                <tr>
                    <th>No</th>
                    <th>Nama Pelanggan</th>
                    <th>No Telp</th>
                    <th>Alamat</th>
                    <th>
                        <form class="filterForm" method="GET" action="{{ route('pembayaran.filter') }}">
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
                        Paket
                    </th>
                    <th>
                        <form id="filterForm" method="GET" action="{{ route('pembayaran.filter') }}">
                            <div class="form-group">

                                <select name="bulan" id="bulan"
                                    onchange="document.getElementById('filterForm').submit();">
                                    <option value="">Pilih Bulan</option>
                                    @foreach (['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $bulan)
                                        <option value="{{ $index + 1 }}"
                                            {{ request('bulan') == $index + 1 ? 'selected' : '' }}>
                                            {{ $bulan }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                        <label for="bulan">Bulan Bayar Terakhir</label>
                    </th>

                    <th>

                        <form class="filterForm" method="GET" action="{{ route('pembayaran.filter') }}">
                            <div class="form-group">
                                <input type="date" name="created_at" id="created_at" value="{{ request('created_at') }}"
                                    onchange="this.form.submit();">
                            </div>
                        </form>
                        Tanggal Pembayaran
                    </th>
                    <th>
                        <form class="filterForm" method="GET" action="{{ route('pembayaran.filter') }}">
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
                        Tanggal Tagih
                    </th>
                    <th>
                        <form class="filterForm" method="GET" action="{{ route('pembayaran.filter') }}">
                            <div class="form-group">
                                <select name="jumlah_pembayaran" id="jumlah_pembayaran" onchange="this.form.submit();">
                                    <option value="">Harga</option>

                                    <option value="50000" {{ request('jumlah_pembayaran') == '50000' ? 'selected' : '' }}>
                                        {{ number_format(50000, 0, ',', '.') }}
                                    </option>
                                    <option value="75000" {{ request('jumlah_pembayaran') == '75000' ? 'selected' : '' }}>
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
                                    <option value="vcr" {{ request('jumlah_pembayaran') == 'vcr' ? 'selected' : '' }}>
                                        vcr
                                    </option>

                                </select>
                            </div>
                        </form>
                        Jumlah Pembayaran
                    </th>
                    <th>Metode Pembayaran</th>
                    <th>Keterangan</th>
                    <th>Admin</th>
                    <th>Edit</th>
                    <th>Hapus</th>
                    <th>Print</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pembayaran as $no => $item)
                    <tr>
                        <td>{{ ($pembayaran->currentPage() - 1) * $pembayaran->perPage() + $loop->iteration }}</td>
                        <td>{{ $item->nama_plg }}</td>
                        <td>{{ $item->no_telepon_plg }}</td>
                        <td>{{ $item->alamat_plg }}</td>
                        <td>{{ $item->paket_plg }}</td>
                        <!-- ambil bulan saja dan convert ke text misal bulan 09 jadi september -->
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pembayaran)->format('F') }}</td>
                        <td>{{ $item->created_at }}</td>
                        <td>{{ $item->tgl_tagih_plg }}</td>
                        <td>{{ number_format($item->jumlah_pembayaran, 0, ',', '.') }}</td>
                        <td>{{ $item->metode_transaksi }}</td>
                        <td>{{ $item->keterangan_plg }}</td>
                        <td>{{ $item->admin_name }}</td>
                        <td>
                            <a href="{{ route('pembayaran.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        </td>
                        <td>
                            <form action="{{ route('pembayaran.destroy', $item->id) }}" method="POST"
                                class="d-inline-block">
                                @csrf

                                <button class="btn btn-danger btn-sm"
                                    onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
                            </form>
                        </td>
                        <td>
                            <button class="btn btn-info btn-sm"
                                onclick="printPayment({{ $no + 1 }}, '{{ $item->nama_plg }}')">Print</button>
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
    <div class="d-flex justify-content-center">
        {{ $pembayaran->links('pagination::bootstrap-4') }}
    </div>

    <script>
        function printPayment(rowNumber, namaPelanggan) {
            var row = document.querySelectorAll('table tbody tr')[rowNumber - 1];
            var nama = row.cells[1].innerText;
            var alamat = row.cells[2].innerText;
            var tglBayar = row.cells[3].innerText;
            var jumlahBayar = row.cells[4].innerText;

            var printContent = `
        <div style="border: 1px solid #000; padding: 20px; width: 600px; margin: 0 auto; font-family: Arial, sans-serif;">
            <div style="border-bottom: 2px solid #000; text-align: center; padding-bottom: 10px; margin-bottom: 10px;">
                <h2 style="font-size: 16px; margin: 0;">KWITANSI PEMBAYARAN INTERNET BULANAN</h2>
            </div>
            <p><strong>Nama Pelanggan:</strong> ${nama}</p>
            <p><strong>Alamat:</strong> ${alamat}</p>
            <p><strong>Tanggal Pembayaran:</strong> ${tglBayar}</p>
            <p><strong>Jumlah Pembayaran:</strong> Rp ${jumlahBayar}</p>
        </div>
        `;

            var printWindow = window.open('', '', 'height=600,width=400');
            printWindow.document.write('<html><head><title>Struk Pembayaran</title></head><body>');
            printWindow.document.write(printContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
@endsection
