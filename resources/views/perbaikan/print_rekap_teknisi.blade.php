<!DOCTYPE html>
<html>
<head>
    <title>Rekap Data Teknisi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
  
    <h4 class="text-center">Dari {{ $startDate->format('d/m/Y') }} sampai {{ $endDate->format('d/m/Y') }}</h4>
    <table>
        <thead>
            <tr>
                <th>Teknisi</th>
                <th>Total Perbaikan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekap as $data)
                <tr>
                    <td>Tim {{ $data->teknisi }}</td>
                    <td>{{ $data->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h4 class="text-right">Total Perbaikan: {{ $totalPerbaikan }}</h4>
</body>
</html>
