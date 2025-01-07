@extends($layout)

@section('konten')
    <div class="mb-3 p-5 mt-4">
        <div class="card">
            <div class="card-header">
                <h4 class="text mb-3 font-weight-bold" style="color: black;">Rekap Pendapatan Harian ({{ $tanggalHariIni }})</h4>
            </div>
            <div class="card-body" style="color: black;">

                <!-- Form untuk memilih tanggal -->
                <form action="{{ route('rekap-harian') }}" method="GET" class="mb-4">
                    <label for="tanggal" style="color: black;">Pilih Tanggal:</label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ $tanggalHariIni }}" class="form-control" style="width: 200px; display: inline-block;">
                    <button type="submit" class="btn btn-primary ml-2">Filter</button>
                </form>

                <table class="table table-bordered" style="color: black;">
                    <thead class="thead-light">
                        <tr>
                            <th>Keterangan</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Total Pendapatan dari Pembayaran Hari Ini</td>
                            <td>Rp. {{ number_format($totalPendapatanHarian) }} | Cash :
                                {{ $totalUserHarian }} Orang </td>
                        </tr>
                        <tr>
                            <td>Total Pemasukan Hari Ini</td>
                            <td>Rp. {{ number_format($totalPemasukan) }}</td>
                        </tr>
                        <tr>
                            <td>Total Pengeluaran Hari Ini</td>
                            <td>Rp. {{ number_format($totalPengeluaran) }}</td>
                        </tr>
                        <tr>
                            <td>Registrasi Pelanggan Baru</td>
                            <td>Rp. {{ number_format($totalRegistrasi) }}</td>
                        </tr>
                        <tr>
                            <td>Total Saldo Hari Ini</td>
                            <td>Rp. {{ number_format($totaljumlahsaldo) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
