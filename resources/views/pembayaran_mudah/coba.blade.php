{{-- resources/views/pembayaran_piutang/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Piutang Hari Ini</title>
</head>
<body>

    <h1>Total Pembayaran Piutang Hari Ini</h1>

    <p><strong>Total Jumlah Pembayaran Piutang: Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</strong></p>
    <p><strong>Jumlah Pelanggan yang Membayar Piutang Hari Ini: {{ $jumlahPelanggan }}</strong></p>

</body>
</html>
