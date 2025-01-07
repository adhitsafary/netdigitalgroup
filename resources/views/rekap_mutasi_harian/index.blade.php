@extends($layout)

@section('konten')
    <div class="container">
        <h1>Mutasi Harian Periode {{ now()->format('F Y') }}</h1>

        <table class="table table-bordered table-responsive">
            <thead class="table table-primary">
                <tr>
                    <th>Tanggal</th>
                    <th>Tagihan Awal</th>
                    <th>Pemasukan Agustus</th>
                    <th>Pemasukan September</th>
                    <th>Saldo Akhir</th>
                    <th>Total Koreksi</th>
                    <th>Belum Tertagih</th>
                    <th>Pemasukan Harian</th>
                    <th>Piutang</th>
                    <th>Piutang Masuk</th>
                    <th>Pendapatan Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekapMutasiHarian as $rekap)
                    <tr>
                        <td>{{ $rekap['tanggal'] }}</td>
                        <td>{{ number_format($rekap['tagihan_awal'], 0, ',', '.') }}</td>
                        <td>{{ number_format($rekap['pemasukan_agustus'], 0, ',', '.') }}</td>
                        <td>{{ number_format($rekap['pemasukan_september'], 0, ',', '.') }}</td>
                        <td>{{ number_format($rekap['saldo_akhir'], 0, ',', '.') }}</td>
                        <td>{{ number_format($rekap['total_koreksi'], 0, ',', '.') }}</td>
                        <td>{{ number_format($rekap['belum_tertagih'], 0, ',', '.') }}</td>
                        <td>{{ number_format($rekap['pemasukan_harian'], 0, ',', '.') }}</td>
                        <td>{{ number_format($rekap['piutang'], 0, ',', '.') }}</td>
                        <td>{{ number_format($rekap['piutang_masuk'], 0, ',', '.') }}</td>
                        <td>{{ number_format($rekap['pendapatan_total'], 2) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@endsection
