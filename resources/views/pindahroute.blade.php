<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Redirect</title>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let routes = [
                '{{ route('index') }}',
                '{{ route('teknisi') }}',
                '{{ route('pelanggan.index') }}'
            ];

            let index = 0;

            function redirect() {
                window.location.href = routes[index]; // Redirect ke route yang sesuai
                index = (index + 1) % routes.length; // Reset ke 0 setelah mencapai akhir
            }

            setTimeout(redirect, 0); // Mulai langsung saat halaman dimuat
            setInterval(redirect, 10000); // Pindah route setiap 10 detik
        });
    </script>
</head>
<body>
    <h1>Auto Redirect Demo</h1>
</body>
</html>
