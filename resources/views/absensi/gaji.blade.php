<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perhitungan Gaji</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Hasil Perhitungan Gaji</h2>
        <form action="{{ url('/hitung-gaji') }}" method="GET">
            <div class="mb-3">
                <label for="user_id" class="form-label">Nama Karyawan</label>
                <select name="user_id" id="user_id" class="form-select" required>
                    <option value="" disabled selected>Pilih Karyawan</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->karyawan->nama }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Hitung Gaji</button>
        </form>

        @if (isset($gaji))
            <div class="mt-5">
                <h4>Detail Gaji:</h4>
                <ul>
                    <li><strong>Nama Karyawan:</strong> {{ $gaji['karyawan'] }}</li>
                    <li><strong>Total Hadir:</strong> {{ $gaji['total_hadir'] }}</li>
                    <li><strong>Total Tidak Hadir:</strong> {{ $gaji['total_tidak_hadir'] }}</li>
                    <li><strong>Gaji Bersih:</strong> Rp{{ number_format($gaji['gaji_bersih'], 0, ',', '.') }}</li>
                </ul>
            </div>
        @endif
    </div>
</body>
</html>
