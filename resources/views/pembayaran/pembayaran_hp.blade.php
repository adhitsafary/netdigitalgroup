@extends($layout)

@section('konten')
    <div class=" p-5 ">

        <div class="container  ">

            <!-- Informasi Total Pembayaran dan Total Pelanggan -->
            <div class="d-flex justify-content-between align-items-center p-3"
                style="background-color: #f8f9fa; color: black; border: 1px solid #ddd;">

                <div class="p-3 text-center" style="background-color: #007bff; color: white; flex: 1; margin-right: 10px;">
                    <strong>Jumlah Pembayaran:</strong>
                    <div style="font-size: 1.5em;">{{ number_format($totalJumlahPembayaran) }}</div>
                </div>

                <div class="p-3 text-center" style="background-color: #28a745; color: white; flex: 1;">
                    <strong>Jumlah Pelanggan:</strong>
                    <div style="font-size: 1.5em;">{{ $totalPelanggan }}</div>
                </div>
            </div>
        </div>

        <div class="container mt-4 mb-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0 text-center">Filter Data Pembayaran</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('pembayaran.pembayaran_hp') }}" method="GET">
                        <!-- Filter Tambahan -->
                        <div class="row">
                            <div class="mb-3 mr-2">
                                <label for="tgl_tagih_plg" class="form-label">Tanggal Tagih</label>
                                <select name="tgl_tagih_plg" id="tgl_tagih_plg" class="form-select">
                                    <option value="">Semua</option>
                                    @for ($i = 1; $i <= 33; $i++)
                                        <option value="{{ $i }}"
                                            {{ request('tgl_tagih_plg') == $i ? 'selected' : '' }}>{{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="paket_plg" class="form-label">Paket</label>
                                <select name="paket_plg" id="paket_plg" class="form-select">
                                    <option value="">Semua</option>
                                    @for ($i = 1; $i <= 7; $i++)
                                        <option value="{{ $i }}"
                                            {{ request('paket_plg') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                    <option value="vcr" {{ request('paket_plg') == 'vcr' ? 'selected' : '' }}>vcr
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3 mr-2">
                                <label for="harga_paket" class="form-label">Harga</label>
                                <select name="harga_paket" id="harga_paket" class="form-select">
                                    <option value="">Semua</option>
                                    @foreach ([50000, 75000, 100000, 105000, 115000, 120000, 125000, 150000, 165000, 175000, 205000, 250000, 265000, 305000, 750000] as $harga)
                                        <option value="{{ $harga }}"
                                            {{ request('harga_paket') == $harga ? 'selected' : '' }}>
                                            {{ number_format($harga, 0, ',', '.') }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="untuk_pembayaran" class="form-label">Jenis pmbyrn</label>
                                <select name="untuk_pembayaran" id="untuk_pembayaran" class="form-select">
                                    <option value="">Semua</option>
                                    <option value="piutang"
                                        {{ request('untuk_pembayaran') == 'piutang' ? 'selected' : '' }}>
                                        Piutang</option>
                                    <option value="tagihan"
                                        {{ request('untuk_pembayaran') == 'tagihan' ? 'selected' : '' }}>
                                        Tagihan</option>
                                    <option value="psb" {{ request('untuk_pembayaran') == 'psb' ? 'selected' : '' }}>PSB
                                    </option>
                                </select>
                            </div>
                        </div>



                        <!-- Filter Tanggal -->
                        <div class="mb-3">
                            <label for="date_start" class="form-label">Tanggal Awal</label>
                            <input type="date" name="date_start" id="date_start" class="form-control"
                                value="{{ $date_start }}">
                        </div>
                        <div class="mb-3">
                            <label for="date_end" class="form-label">Tanggal Akhir</label>
                            <input type="date" name="date_end" id="date_end" class="form-control"
                                value="{{ $date_end }}">
                        </div>

                        <!-- Pencarian -->
                        <div class="mb-3">
                            <label for="search" class="form-label">Pencarian</label>
                            <input type="text" name="search" id="search" class="form-control"
                                value="{{ request('search') }}" placeholder="Masukkan kata kunci">
                        </div>

                        <!-- Tombol -->
                        <div class="d-flex justify-content-between align-items-center">
                            <button type="submit" class="btn btn-primary w-100 me-2">Filter</button>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    Ekspor
                                </button>
                                <div class="dropdown-menu">
                                    <a href="{{ route('pembayaran.export', [
                                        'format' => 'pdf',
                                        'date_start' => request('date_start'),
                                        'date_end' => request('date_end'),
                                        'tgl_tagih_plg' => request('tgl_tagih_plg'),
                                        'paket_plg' => request('paket_plg'),
                                        'harga_paket' => request('harga_paket'),
                                        'search' => request('search'),
                                        'untuk_pembayaran' => request('untuk_pembayaran'),
                                    ]) }}"
                                        class="dropdown-item">PDF</a>
                                    <a href="{{ route('pembayaran.export', [
                                        'format' => 'excel',
                                        'date_start' => request('date_start'),
                                        'date_end' => request('date_end'),
                                        'tgl_tagih_plg' => request('tgl_tagih_plg'),
                                        'paket_plg' => request('paket_plg'),
                                        'harga_paket' => request('harga_paket'),
                                        'search' => request('search'),
                                        'untuk_pembayaran' => request('untuk_pembayaran'),
                                    ]) }}"
                                        class="dropdown-item">Excel</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>




        <!-- Tabel Pembayaran -->
        <div class="container mt-4">
            @forelse ($pembayaran as $no => $item)
                <div class="card mb-3 shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <strong>{{ $item->nama_plg }}</strong>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>No Telepon:</strong> {{ $item->no_telepon_plg }}</p>
                        <p class="mb-1"><strong>Alamat:</strong> {{ $item->alamat_plg }}</p>
                        <p class="mb-1"><strong>Paket:</strong> {{ $item->paket_plg }}</p>
                        <p class="mb-1"><strong>Bulan Bayar:</strong>
                            {{ \Carbon\Carbon::parse($item->tanggal_pembayaran)->format('F Y') }}</p>
                        <p class="mb-1"><strong>Tanggal Pembayaran:</strong> {{ $item->created_at }}</p>
                        <p class="mb-1"><strong>Tanggal Tagih:</strong> {{ $item->tgl_tagih_plg }}</p>
                        <p class="mb-1"><strong>Jumlah Pembayaran:</strong>
                            Rp{{ number_format($item->jumlah_pembayaran, 0, ',', '.') }}</p>
                        <p class="mb-1"><strong>Metode Pembayaran:</strong> {{ $item->metode_transaksi }}</p>
                        <p class="mb-1"><strong>Pembayaran:</strong> {{ $item->untuk_pembayaran }}</p>
                        <p class="mb-1"><strong>Keterangan:</strong> {{ $item->keterangan_plg }}</p>
                        <p class="mb-1"><strong>Admin:</strong> {{ $item->admin_name }}</p>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <a href="{{ route('pembayaran.edit', $item->id) }}" class="btn btn-primary btn-sm">
                            <img src="{{ asset('asset/img/icon/edit.png') }}" alt="Edit"
                                style="height: 20px; width: 20px;">
                        </a>
                        <form action="{{ route('pembayaran.destroy', $item->id) }}" method="POST"
                            class="d-inline-block">
                            @csrf
                            <button onclick="return confirm('Yakin ingin menghapus data ini?')"
                                class="btn btn-danger btn-sm">
                                <img src="{{ asset('asset/img/icon/delete.png') }}" alt="Hapus"
                                    style="height: 20px; width: 20px;">
                            </button>
                        </form>
                        <button class="btn btn-info btn-sm"
                            onclick="printPayment({{ $no + 1 }}, '{{ $item->nama_plg }}')">
                            <img src="{{ asset('asset/img/icon/printer.png') }}" alt="Print"
                                style="height: 20px; width: 20px;">
                        </button>
                    </div>
                </div>
            @empty
                <div class="alert alert-warning text-center">
                    Tidak ada data pembayaran ditemukan.
                </div>
            @endforelse
        </div>

    </div>
    <div class="d-flex justify-content-center">
        {{ $pembayaran->links('pagination::bootstrap-4') }}
    </div>

    <script>
        function printPayment(rowNumber, namaPelanggan) {
            var row = document.querySelectorAll('table tbody tr')[rowNumber - 1];
            var nama = row.cells[1].innerText;
            var alamat = row.cells[3].innerText;
            var paket = row.cells[4].innerText;
            var tglBayar = row.cells[6].innerText;
            var jumlahBayar = row.cells[8].innerText;
            var id_plg = row.cells[13].innerText;
            var admin = row.cells[12].innerText;

            // Konversi jumlahBayar ke angka
            jumlahBayar = parseInt(jumlahBayar.replace(/\D/g, ''), 10); // Hilangkan simbol "Rp" dan tanda baca

            // Hitung jumlah sebelum PPN (mengurangi PPN 11% terlebih dahulu)
            var jumlahTanpaPPN = jumlahBayar / 1.11 - 2670;

            // Hitung PPN (11% dari jumlahTanpaPPN)
            var ppn = jumlahTanpaPPN * 0.11;

            // Tambahkan Bea Meterai
            var beaMeterai = 3000;

            // Hitung total tagihan (jumlahTanpaPPN + PPN + Bea Meterai)
            var totalTagihan = jumlahTanpaPPN + ppn + beaMeterai;

            var printContent = `
<div style="border: 1px solid #000; padding: 20px; width: 800px; margin: 20px auto; font-family: 'Arial', sans-serif; background-color: #fff; position: relative; height: 1123px;">
    <!-- Watermark LUNAS -->
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 120px; color: rgba(0, 0, 0, 0.05); font-weight: bold; z-index: 0;">
        LUNAS
    </div>

    <!-- Header -->
    <div style="position: relative; margin-bottom: 20px; text-align: center;">
        <h2 style="font-size: 18px; margin: 0; color: #333; letter-spacing: 2px; z-index: 1;">
            INVOICE PEMBAYARAN INTERNET BULANAN
        </h2>
        <p style="font-size: 14px; margin: 5px 0; color: #555; letter-spacing: 1px;">Terima kasih telah menggunakan layanan kami!</p>
    </div>

    <!-- Informasi Pelanggan -->
    <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 20px; z-index: 1; position: relative;">
        <tr>
            <td style="width: 50%; vertical-align: top; padding: 10px; border: 1px solid #000;">
                <strong>Kepada/To:</strong><br>
                ${nama}<br>
                ${alamat}
            </td>
            <td style="width: 50%; vertical-align: top; text-align: right; padding: 10px; border: 1px solid #000;">
                <strong>No. Tagihan/Invoice No:</strong> ${id_plg}/${tglBayar}<br>
                <strong>Nomor Pelanggan/Customer:</strong> ${id_plg}<br>
                <strong>Tanggal Pembayaran:</strong> ${tglBayar}<br>
            </td>
        </tr>
    </table>

    <!-- Tabel Rincian -->
    <table style="width: 100%; border-collapse: collapse; font-family: Arial, sans-serif; margin: 20px 0;">
    <tbody>
        <tr>
            <td style="padding: 10px; border: 1px solid #000;">Paket : ${paket}</td>
            <td style="padding: 10px; text-align: right; border: 1px solid #000;">Rp ${jumlahTanpaPPN.toLocaleString('id-ID')}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #000;">PPN 11%</td>
            <td style="padding: 10px; text-align: right; border: 1px solid #000;">Rp ${ppn.toLocaleString('id-ID')}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #000;">Bea Meterai</td>
            <td style="padding: 10px; text-align: right; border: 1px solid #000;">Rp ${beaMeterai.toLocaleString('id-ID')}</td>
        </tr>
        <tr>
            <td style="padding: 10px; font-weight: bold; border: 1px solid #000;">Tagihan Bulan Ini/Current Charges</td>
            <td style="padding: 10px; text-align: right; font-weight: bold; border: 1px solid #000;">Rp ${totalTagihan.toLocaleString('id-ID')}</td>
        </tr>
           <tr>
            <td style="padding: 10px;  border: 1px solid #000;">Admin</td>
            <td style="padding: 10px; text-align: right;  border: 1px solid #000;"> ${admin.toLocaleString('id-ID')}</td>
        </tr>
    </tbody>
</table>

    <!-- Footer Konten -->
    <div style="font-size: 12px; text-align: justify; border-top: 1px solid #000; padding-top: 10px; z-index: 1; position: relative;">
        <strong>Pengumuman Penting/Important Information:</strong><br>
        - Jasa internet dikenakan PPN 11% sesuai peraturan.<br>
        - Pembayaran wajib dilakukan sebelum jatuh tempo untuk menghindari denda keterlambatan.<br>
        - Informasi ini adalah resmi dan tidak dapat diganggu gugat.
    </div>

    <!-- Footer di Bagian Bawah -->
    <div style="font-size: 14px; text-align: center; position: absolute; bottom: 20px; left: 0; width: 100%;">
        <div style="border: 1px solid #000; padding: 10px; margin: 0 auto; width: 95%; background-color: #f9f9f9; box-shadow: 0px 4px 6px rgba(0,0,0,0.1);">
            <p style="margin: 0; line-height: 1.6;">
                <strong>Terima kasih atas kepercayaan Anda menggunakan layanan kami!</strong><br>
                Kami berkomitmen untuk terus memberikan pelayanan terbaik kepada Anda. Apabila terdapat kendala atau pertanyaan lebih lanjut,
                jangan ragu untuk menghubungi layanan pelanggan kami melalui telepon atau email yang tertera di portal resmi kami.
                Kepuasan Anda adalah prioritas utama kami.
            </p>
        </div>
    </div>
</div>
`;




            var printWindow = window.open('', '', 'height=800,width=800');
            printWindow.document.write('<html><head><title>Struk Pembayaran</title>');
            printWindow.document.write('<style>body { margin: 0; padding: 0; }</style></head><body>');
            printWindow.document.write(printContent);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }
    </script>
@endsection
