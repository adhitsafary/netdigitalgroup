@extends('layout_login')

@section('konten')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Absensi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 600px;
        }

        #video {
            width: 100%;
            max-width: 400px;
            border: 2px solid #ddd;
            border-radius: 10px;
        }

        #canvas {
            display: none;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .form-label {
            font-weight: bold;
        }

        #response-message {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            background-color: rgb(85, 85, 85);
            color: #333;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-body {
            padding: 30px;
        }

        .mt-4 {
            margin-top: 20px;
        }

        .alert {
            margin-top: 20px;
        }

        /* Loading Spinner */
        .loading-spinner {
            display: none;
            width: 3rem;
            height: 3rem;
            border: 0.25rem solid #f3f3f3;
            border-top: 0.25rem solid #007bff;
            border-radius: 50%;
            animation: spin 2s linear infinite;
            margin: 0 auto;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>

    <div class="container mt-2">
      

        <!-- Card Form -->
        <div class="card ml-4 mr-4 mb-5">
        <h6 class="text-center" style="font-weight: 700;">Form Absensi kehadiran</h6>
            <div class="card-body">
                <form id="absensi-form">
                    <div class="mb-4">
                        <label for="titik_lokasi" class="form-label">Titik Lokasi</label>
                        <input type="text" id="titik_lokasi" name="titik_lokasi" class="form-control" required readonly>
                    </div>

                    <div class="mb-4 text-center">
                        <label for="foto" class="form-label">Foto</label>
                        <video id="video" autoplay></video>
                        <canvas id="canvas"></canvas>
                        <br>
                        <button type="button" id="snap" class="btn btn-danger mt-3">Ambil Foto</button>
                    </div>

                    <input type="hidden" id="foto" name="foto">

                    <!-- Jam Pulang Input (Tersembunyi) -->
                    <div class="mb-4" id="jam_pulang_group" style="display: none;">
                        <label for="jam_pulang" class="form-label">Jam Pulang</label>
                        <input type="text" id="jam_pulang" name="jam_pulang" class="form-control" readonly>
                    </div>

                    <button type="submit" class="btn btn-danger w-100 mt-4">Submit</button>
                </form>

                <!-- Loading Spinner -->
                <div id="loading" class="loading-spinner"></div>

                <!-- Response Message -->
                <div id="response-message" class="alert" style="display: none;"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Mengakses kamera
        const video = document.querySelector("#video");
        const canvas = document.querySelector("#canvas");
        const context = canvas.getContext("2d");

        // Mengaktifkan kamera
        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then((stream) => {
                video.srcObject = stream;
            })
            .catch((err) => {
                console.error("Error accessing camera: ", err);
                alert("Kamera tidak tersedia di perangkat Anda.");
            });

        // Menangkap gambar dari kamera saat tombol ditekan
        document.querySelector("#snap").addEventListener("click", () => {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.drawImage(video, 0, 0);
            const dataURL = canvas.toDataURL("image/png");
            document.querySelector("#foto").value = dataURL;
            alert("Foto berhasil diambil!");
        });

        // Mendapatkan lokasi pengguna
        navigator.geolocation.getCurrentPosition(
            async (position) => {
                    const latitude = position.coords.latitude;
                    const longitude = position.coords.longitude;

                    // Menampilkan koordinat sementara
                    document.querySelector("#titik_lokasi").value = `${latitude}, ${longitude}`;

                    try {
                        const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}`);
                        const data = await response.json();
                        const placeName = data.display_name || "Lokasi tidak diketahui";

                        // Menambahkan latitude dan longitude di belakang nama lokasi
                        document.querySelector("#titik_lokasi").value = `${placeName} ( ${latitude}, ${longitude} )`;
                    } catch (error) {
                        console.error("Error fetching location name:", error);
                        alert("Gagal mendapatkan nama lokasi. Hanya menyimpan koordinat.");
                        document.querySelector("#titik_lokasi").value = `${latitude}, ${longitude}`;
                    }
                },
                (error) => {
                    let errorMessage = "Gagal mendapatkan lokasi.";
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            errorMessage = "Akses lokasi ditolak. Mohon izinkan akses lokasi.";
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMessage = "Informasi lokasi tidak tersedia.";
                            break;
                        case error.TIMEOUT:
                            errorMessage = "Permintaan lokasi melebihi batas waktu.";
                            break;
                        default:
                            errorMessage = "Terjadi kesalahan dalam mendapatkan lokasi.";
                    }
                    alert(errorMessage);
                    console.error("Geolocation Error:", error);
                }
        );
        // Debugging Script
        document.querySelector("#absensi-form").addEventListener("submit", async (e) => {
            e.preventDefault();

            // Ambil data dari form
            const formData = new FormData(e.target);

            // Logging data sebelum dikirim
            console.log("Data yang dikirim:", Object.fromEntries(formData.entries()));

            // Menampilkan loading spinner
            document.querySelector("#loading").style.display = "block";

            // Kirim data menggunakan Fetch API
            try {
                const response = await fetch("/absensi/masuk", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                    },
                    body: formData,
                });

                // Parsing hasil respons
                const result = await response.json();

                // Sembunyikan loading spinner
                document.querySelector("#loading").style.display = "none";

                if (response.ok) {
                    document.querySelector("#response-message").innerText = "Absensi berhasil disimpan!";
                    document.querySelector("#response-message").classList.add("alert-success");
                    document.querySelector("#response-message").classList.remove("alert-danger");

                    // Cek apakah absensi sudah dua kali (jam_pulang ada)
                    if (result.data.jam_pulang) {
                        document.querySelector("#jam_pulang_group").style.display = "block";
                        document.querySelector("#jam_pulang").value = result.data.jam_pulang;
                    } else {
                        document.querySelector("#jam_pulang_group").style.display = "none";
                    }

                } else {
                    document.querySelector("#response-message").innerText = "Perhatian: " + (result.message || "Terjadi kesalahan.");
                    document.querySelector("#response-message").classList.add("alert-danger");
                    document.querySelector("#response-message").classList.remove("alert-success");
                }

                // Tampilkan pesan response
                document.querySelector("#response-message").style.display = "block";
            } catch (error) {
                console.error("Fetch Error:", error);
                document.querySelector("#loading").style.display = "none";
                document.querySelector("#response-message").innerText = "Terjadi kesalahan jaringan.";
                document.querySelector("#response-message").classList.add("alert-danger");
                document.querySelector("#response-message").style.display = "block";
            }
        });
    </script>

</body>

</html>
@endsection