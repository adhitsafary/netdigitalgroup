@extends($layout)

@section('konten')
    <div class="container-fluid" id="container-wrapper">
        <div class="d-sm-flex align-items-center justify-content-between mb-2">


        </div>

        <div class="row mb-3">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card-biru_tua h-70">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <div class="text text-white font-weight-bold  mb-1">Pemasukan KANTOR -
                                    Pengeluaran
                                </div>
                                <div class=" h6 mb-0 mr-0 font-weight-bold text-white font-bold">Rp
                                    {{ number_format($totaljumlahsaldo, 0, ',', '.') }} || {{ $totalUserHarian }}</div>
                                <div class="mb-0 text-muted">
                                    <span class="text text-white font-weight-bold "></span>
                                    <span
                                        class="text text-white font-weight-bold "></span>

                                    <span class="text text-white font-weight-bold "></span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <!-- New User Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card-violet h-70">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class=" text text-white font-weight-bold  mb-1">Total Semua User yang
                                    Membayar
                                    hari ini
                                </div>
                                <div class=" mb-0 font-weight-bold text text-white">Rp
                                    {{ number_format($total_user_bayar, 0, ',', '.') }} || {{ $total_jml_user }}</div>
                                <!-- Menampilkan pendapatan dengan format rupiah -->
                                <div class="mb-0 text-muted">
                                    <span class="text text-white font-weight-bold "></span>
                                    <span class="text text-white mr-2 font-weight-bold "><i
                                            class="text text-white"></i>
                                        </span>
                                    <span class="text text-white  font-weight-bold ">
                                    </span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <!-- New User Card Example -->
            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card-hijau  h-70">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ route('pelanggan.redirect') }}">
                                <div class="col mr-2">
                                    <div class=" text text-white font-weight-bold  mb-1">Total Tagihan hari
                                        ini
                                    </div>
                                    <div class=" mb-0 font-weight-bold text text-white">Rp
                                        {{ number_format($totalTagihanHariIni, 0, ',', '.') }} || {{ $jumlahPelangganMembayarHariIni }}</div>
                                    <!-- Menampilkan pendapatan dengan format rupiah -->
                                    <div class="mb-0 text-muted">
                                        <span class="text text-white font-weight-bold "></span>
                                        <span class="text text-white mr-2 font-weight-bold "><i
                                                class="text text-white"></i>
                                            </span>
                                        <span class="text text-white  font-weight-bold ">
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New User Card Example -->
            <div class="col-xl-2 col-md-6 mb-4">
                <div class="card-kuning  h-70">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                        <a href="{{ route('pelanggan.sudahbayar') }}">
                                <div class="col mr-2">
                                    <div class=" text text-white font-weight-bold  mb-1"> Tertagih
                                    </div>
                                    <div class=" mb-0 font-weight-bold text text-white">Rp
                                    Rp {{ number_format($totalPendapatanharian_semua, 0, ',', '.') }} ||
                                        {{ $totalUserHarian_semua }}</div>
                                    <!-- Menampilkan pendapatan dengan format rupiah -->
                                    <div class="mb-0 text-muted">
                                        <span class="text text-white font-weight-bold "></span>
                                        <span class="text text-white mr-2 font-weight-bold "><i
                                                class="text text-white"></i>
                                            </span>
                                        <span class="text text-white  font-weight-bold ">
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>


             <!-- New User Card Example -->
             <div class="col-xl-2 col-md-6 mb-4">
                <div class="card-merah  h-70">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <a href="{{ route('pelanggan.belumbayar') }}">
                                <div class="col mr-2">
                                    <div class=" text text-white font-weight-bold  mb-1"> Sisa Tagihan
                                    </div>
                                    <div class=" mb-0 font-weight-bold text text-white">Rp
                                    Rp {{ number_format($totalTagihanTertagih, 0, ',', '.') }} ||
                                        {{ $totalUserTertagih }}</div>
                                    <!-- Menampilkan pendapatan dengan format rupiah -->
                                    <div class="mb-0 text-muted">
                                        <span class="text text-white font-weight-bold "></span>
                                        <span class="text text-white mr-2 font-weight-bold "><i
                                                class="text text-white"></i>
                                            </span>
                                        <span class="text text-white  font-weight-bold ">
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>


           <!-- Chart Bar dan Line -->
<div class="col-xl-70 col-lg-8">
    <!-- Memperbesar tampilan card-body -->
    <div class="card-magenta" style="font-size: 1.5rem; height: 500px;"> <!-- Menambah ukuran font dan tinggi card -->
        <div class="card-body" style="height: 100%;"> <!-- Memastikan card-body mengikuti tinggi card -->
            <h6 class="text text-white font-weight-bold">Tabel Pembayaran Per Hari</h6>
            <!-- Membesarkan judul -->
            <div class="chart-area" style="height: 400px;"> <!-- Menyesuaikan tinggi area chart -->
                <canvas id="pendapatanChart" width="850" height="300"></canvas>
                <!-- Untuk Bar/Line Chart -->
            </div>
        </div>
    </div>
</div>



            <!-- Pie Chart -->
            <div class="card-biru_tua col-xl-4 col-lg- mt-0 h-100">
                <div class="p-3 ">
                    <div class="py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 pl-3 font-weight-bold text-white">PERSANTE PEMBAYARAN</h6>
                    </div>
                    <div class="chart-area">
                        <canvas id="myPieChart"></canvas> <!-- Untuk Pie Chart -->
                    </div>
                    <!-- Row untuk Baru Terbayar dan Total Tagihan -->
                    <div class="mt-3 ml-2 d-flex justify-content-between">

                     <!-- Total Tagihan -->
                     <div class="bg-success py-3 d-flex flex-column align-items-start justify-content-center"
                            style="width: 48%;">
                            <h6 class="m-0 pl-3 font-weight-bold text-white">Total Tagihan</h6>
                            <div class="text-white h6 mb-0 font-weight-bold pl-3">
                                Rp {{ number_format($totalTagihanHariIni, 0, ',', '.') }} <br> User :
                                {{ $totalUserTertagih }}
                            </div>
                        </div>
                        <!-- Baru Terbayar -->
                        <div class="bg-warning py-3 d-flex flex-column align-items-start justify-content-center ml-2"
                            style="width: 48%; margin-right: 10px;">
                            <h6 class="m-0 pl-3 font-weight-bold text-white">Tertagih</h6>
                            <div class="text-white h6 mb-0 font-weight-bold pl-3">
                                Rp {{ number_format($totalPendapatanharian_semua, 0, ',', '.') }} <br> User :
                                {{ $totalUserHarian_semua }}
                            </div>
                        </div>
                        <!--sisa tagihan-->
                        <div class="bg-danger py-3 d-flex flex-column align-items-start justify-content-center mr-2"
                            style="width: 48%;">
                            <h6 class="m-0 pl-3 font-weight-bold text-white">Sisa Tagihan</h6>
                            <div class="text-white h6 mb-0 font-weight-bold pl-3">
                                Rp {{ number_format($totalTagihanTertagih, 0, ',', '.') }} <br> User :
                                {{ $totalUserHarian_semua }}
                            </div>
                        </div>
                       

                    </div>
                </div>

            </div>

            <div class="col-xl-8 col-lg-7 mb-4 ">
                <div class="card h-100">
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h5 class="m-0 font-weight-bold text-primary">Pemberitahuan</h5>
                        <a class="m-0 float-right btn btn-danger btn-sm" href="/pemberitahuan">Lihat semua <i
                                class="fas fa-chevron-right"></i></a>
                    </div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Pesan</th>
                                <th>Tanggal</th>

                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pemberitahuan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->pesan }}</td>
                                    <td>{{ $item->updated_at }}</td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak ada pemberitahuan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="card-footer"></div>
                </div>
            </div>
            <div class="col-xl-4 col-lg-5 mt-4 ">
                <div class="card">
                    <div class="card-header bg-primary d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-light">Target Marketing</h6>
                    </div>
                    <div class=" ">

                        <div class="chart-area">
                            <canvas id="myPieChart1"></canvas> <!-- Untuk Pie Chart -->
                        </div>
                        <!-- Row untuk Baru Terbayar dan Total Tagihan -->
                        <div class="mt-3 ml-2 d-flex justify-content-between">
                            <!-- Baru Terbayar -->
                            <div class="bg-warning py-3 d-flex flex-column align-items-start justify-content-center"
                                style="width: 48%; margin-right: 10px;">
                                <h6 class="m-0 pl-3 font-weight-bold text-white">Tercapai</h6>
                                <div class="text-white h5 mb-0 font-weight-bold pl-3">
                                    {{ number_format($hasil_target) }}
                                </div>
                            </div>
                            <!--sisa tagihan-->
                            <div class="bg-danger py-3 d-flex flex-column align-items-start justify-content-center mr-2"
                                style="width: 48%;">
                                <h6 class="m-0 pl-3 font-weight-bold text-white">Sisa</h6>
                                <div class="text-white h5 mb-0 font-weight-bold pl-3">
                                    {{ number_format($sisa_target) }}
                                </div>
                            </div>
                            <!-- Total Tagihan -->
                            <div class="bg-success py-3 d-flex flex-column align-items-start justify-content-center"
                                style="width: 48%;">
                                <h6 class="m-0 pl-3 font-weight-bold text-white">Jumlah Target</h6>
                                <div class="text-white h5 mb-0 font-weight-bold pl-3">
                                    {{ number_format($jumlah_target) }}
                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </div>






            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

            <script>
                // Data dari backend (controller Laravel) untuk chart batang/garis
                const labels = @json($labels); // Label untuk tanggal (1-30)
                const totalUsers = @json($totalUsers); // Jumlah user per tanggal
                const totalPembayaran = @json($totalPembayaran); // Total pembayaran per tanggal

                // Inisialisasi Bar dan Line Chart
                const ctx1 = document.getElementById('pendapatanChart').getContext('2d');
                const pendapatanChart = new Chart(ctx1, {
                    type: 'bar', // Tipe chart batang (bar)
                    data: {
                        labels: labels, // Label (Tanggal 1-30)
                        datasets: [{
                                label: 'Jumlah Pengguna Bayar',
                                data: totalUsers, // Data jumlah pengguna per hari
                                backgroundColor: 'rgba(255, 255, 255, 0.2)', // Warna batang putih semi transparan
                                borderColor: 'rgb(255, 157, 0)', // Warna border batang putih
                                borderWidth: 1,
                                pointRadius: 8, // Mengatur ukuran titik (besar)
                                pointHoverRadius: 10 // Mengatur ukuran titik saat di-hover (lebih besar)
                            },
                            {
                                label: 'Total Pembayaran (Rp)',
                                data: totalPembayaran, // Data total pembayaran per hari
                                backgroundColor: 'rgba(255, 255, 255, 0.2)', // Warna grafik garis putih semi transparan
                                borderColor: 'rgb(255, 157, 0)', // Warna border garis putih
                                borderWidth: 1,
                                type: 'line', // Grafik tipe garis (line)
                                pointRadius: 8, // Mengatur ukuran titik untuk garis (besar)
                                pointHoverRadius: 10 // Mengatur ukuran titik saat di-hover (lebih besar)
                            }
                        ]
                    },
                    options: {
                        layout: {
                            padding: 0 // Menghilangkan padding pada layout
                        },
                        scales: {
                            y: {
                                beginAtZero: true, // Memulai grafik dari 0 pada sumbu Y
                                ticks: {
                                    color: 'white' // Warna label sumbu Y menjadi putih
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.2)' // Warna grid sumbu Y putih semi transparan
                                }
                            },
                            x: {
                                ticks: {
                                    color: 'white' // Warna label sumbu X menjadi putih
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.2)' // Warna grid sumbu X putih semi transparan
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                labels: {
                                    color: 'white', // Warna label legenda menjadi putih
                                    font: {
                                        style: 'bold' // Membuat label legenda bold
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(255, 255, 255, 0.8)', // Background tooltip putih semi transparan
                                titleColor: 'black', // Warna judul tooltip hitam
                                bodyColor: 'black', // Warna isi tooltip hitam
                                borderColor: 'rgba(255, 255, 255, 1)', // Warna border tooltip putih
                                borderWidth: 1,
                                titleFont: {
                                    weight: 'bold' // Membuat teks judul tooltip bold
                                }
                            }
                        }
                    }
                });





                // Data untuk Pie Chart
                var totalTagihanHariIni = @json($totalTagihanHariIni); // Total tagihan hari ini
                var totalPendapatanharian_semua = @json($totalPendapatanharian_semua); // Total pendapatan harian semua

                // Inisialisasi Pie Chart
                var ctx2 = document.getElementById("myPieChart").getContext('2d');
                var myPieChart = new Chart(ctx2, {
                    type: 'pie', // Menggunakan tipe pie untuk lingkaran penuh
                    data: {
                        datasets: [{
                            data: [totalTagihanHariIni - totalPendapatanharian_semua,
                                totalPendapatanharian_semua
                            ], // Data dari controller
                            backgroundColor: ['#e74c3c', '#FFBB00'], // Warna untuk bagian chart
                            hoverBackgroundColor: ['#c0392b', '#FFBB00'], // Warna saat di-hover
                            hoverBorderColor: "rgba(234, 236, 244, 1)", // Border saat di-hover
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            titleFontColor: "white", // Judul tooltip putih
                            bodyFontColor: "white", // Isi tooltip putih
                            titleFontStyle: "bold", // Judul tooltip bold
                            bodyFontStyle: "bold", // Isi tooltip bold
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var dataset = data.datasets[tooltipItem.datasetIndex];
                                    var total = dataset.data.reduce(function(previousValue, currentValue) {
                                        return previousValue + currentValue;
                                    });
                                    var currentValue = dataset.data[tooltipItem.index];
                                    var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
                                    return data.labels[tooltipItem.index] + ': ' + percentage + '%';
                                }
                            }
                        },
                        legend: {
                            display: true, // Tampilkan legenda untuk menjelaskan chart
                            position: 'bottom', // Posisi legenda di bawah chart
                            labels: {
                                fontColor: "white", // Warna teks legenda menjadi putih
                                fontStyle: "bold", // Teks legenda menjadi bold
                                usePointStyle: true // Menjaga ikon lingkaran di legend
                            }
                        },
                        cutoutPercentage: 0, // Tidak ada ruang di tengah lingkaran (untuk pie chart penuh)
                        plugins: {
                            labels: {
                                render: 'label',
                                fontColor: 'white', // Membuat label chart menjadi putih
                                fontStyle: 'bold' // Membuat teks label chart menjadi bold
                            }
                        }
                    }
                });



                //Target Marketing
                // Data untuk Pie Chart
                var sisa_target = @json($sisa_target); // Total tagihan hari ini
                var jumlah_target = @json($jumlah_target); // Total pendapatan harian semua

                // Inisialisasi Pie Chart
                var ctx2 = document.getElementById("myPieChart1").getContext('2d');
                var myPieChart1 = new Chart(ctx2, {
                    type: 'pie', // Menggunakan tipe pie untuk lingkaran penuh
                    data: {
                        datasets: [{
                            data: [sisa_target - jumlah_target,
                                jumlah_target
                            ], // Data dari controller
                            backgroundColor: ['#00BFFF', '#0000CD '], // Warna untuk bagian chart
                            hoverBackgroundColor: ['#00BFFF', '#0000CD '], // Warna saat di-hover
                            hoverBorderColor: "rgba(234, 236, 244, 1)", // Border saat di-hover
                        }],
                    },
                    options: {
                        maintainAspectRatio: false,
                        tooltips: {
                            backgroundColor: "rgb(255,255,255)",
                            titleFontColor: "white", // Judul tooltip putih
                            bodyFontColor: "white", // Isi tooltip putih
                            titleFontStyle: "bold", // Judul tooltip bold
                            bodyFontStyle: "bold", // Isi tooltip bold
                            borderColor: '#dddfeb',
                            borderWidth: 1,
                            xPadding: 15,
                            yPadding: 15,
                            displayColors: false,
                            caretPadding: 10,
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    var dataset = data.datasets[tooltipItem.datasetIndex];
                                    var total = dataset.data.reduce(function(previousValue, currentValue) {
                                        return previousValue + currentValue;
                                    });
                                    var currentValue = dataset.data[tooltipItem.index];
                                    var percentage = Math.floor(((currentValue / total) * 100) + 0.5);
                                    return data.labels[tooltipItem.index] + ': ' + percentage + '%';
                                }
                            }
                        },
                        legend: {
                            display: true, // Tampilkan legenda untuk menjelaskan chart
                            position: 'bottom', // Posisi legenda di bawah chart
                            labels: {
                                fontColor: "white", // Warna teks legenda menjadi putih
                                fontStyle: "bold", // Teks legenda menjadi bold
                                usePointStyle: true // Menjaga ikon lingkaran di legend
                            }
                        },
                        cutoutPercentage: 0, // Tidak ada ruang di tengah lingkaran (untuk pie chart penuh)
                        plugins: {
                            labels: {
                                render: 'label',
                                fontColor: 'white', // Membuat label chart menjadi putih
                                fontStyle: 'bold' // Membuat teks label chart menjadi bold
                            }
                        }
                    }
                });
            </script>




        </div>

        <!-- Modal Logout -->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalLabelLogout" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabelLogout">Ohh No!</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to logout?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                        <a href="/logout" class="btn btn-primary">Logout</a>
                    </div>
                </div>
            </div>
        </div>



        <!-- Footer -->
        <br><br>
        <footer class="sticky-footer bg-white">
            <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>copyright &copy;
                        <script>
                            document.write(new Date().getFullYear());
                        </script> - developed by
                        <b><a href="" target="_blank">NetNet Digital Group</a></b>
                    </span>
                </div>
            </div>
        </footer>
        </footer>
    @endsection


    <!-- CSS untuk Running Text -->
    <style>
        .running-text-container {
            background-color: rgb(48, 48, 48);
            /* Mengubah latar belakang menjadi hitam */
            color: white;
            /* Mengubah warna teks menjadi putih untuk kontras yang baik */
            overflow: hidden;
            white-space: nowrap;
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            height: 50px;
            /* Tinggi kontainer */
        }

        .running-text {
            display: inline-block;
            font-size: 1.2rem;
            /* Sedikit memperbesar ukuran font */
            animation: scroll-left 20s linear infinite;
            /* Durasi animasi tetap */
        }

        @keyframes scroll-left {
            0% {
                transform: translateX(100%);
            }

            100% {
                transform: translateX(-100%);
            }
        }
    </style>

    <!-- HTML untuk Running Text dengan Data -->
    <div class="running-text-container">
        <div class="running-text">
            <span>
                Sisa Tagihan Hari Ini: Rp {{ number_format($totalTagihanTertagih, 0, ',', '.') }} User:
                {{ $totalUserTertagih }} ||
                Tagihan Hari Ini: Rp {{ number_format($totalTagihanHariIni, 0, ',', '.') }} User:
                {{ $jumlahPelangganMembayarHariIni }} ||
                Tertagih: Rp {{ number_format($totalPendapatanharian_semua, 0, ',', '.') }} User:
                {{ $totalUserHarian_semua }}
            </span>
        </div>
    </div>
