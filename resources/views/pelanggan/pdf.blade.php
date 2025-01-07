<!DOCTYPE html>
<html>
<head>
    <title>Data Pelanggan</title>
    <style>
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
    <h1>Data Pelanggan</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pelanggan</th>
                <th>Alamat</th>
                <th>Paket</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pelanggan as $no => $item)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $item->nama_plg }}</td>
                    <td>{{ $item->alamat_plg }}</td>
                    <td>{{ $item->paket_plg}}</td>
                    <td>{{$item ->status_pembayaran}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
