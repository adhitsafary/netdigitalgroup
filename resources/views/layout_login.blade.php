<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="{{ asset('template2/img/logo/logo.png') }}" rel="icon">
    <title>NetDigitalGroup</title>
    <link href="{{ asset('template2/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template2/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('template2/css/ruang-admin.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('template2/css/ruang-admin.min.css') }}">

</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-light accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/absensi/absen">
                <div class="sidebar-brand-icon">
                    <img src="{{ asset('template2/img/logo/logo2.png') }}">
                </div>
                <div class="sidebar-brand-text mx-3">Net Digital Group</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item active">
                <a class="nav-link" href="/absensi/absen">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>


            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBootstrap1000"
                    aria-expanded="true" aria-controls="collapseBootstrap1000">
                    <img src="{{ asset('asset/img/icon/kerja.png') }}" alt="Gambar Pelanggan"
                        style="width: 30px; height: auto; margin-left: 10px;" class="mr-2">
                    <span class="font-weight-bold " style="color: black">Pekerjaan Saya</span>
                </a>
                <div id="collapseBootstrap1000" class="collapse" aria-labelledby="collapseBootstrap1000"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded font-weight-bold" style="color: black">
                        <a class="collapse-item" href="#">Pekerjaan Saya</a>
                    </div>
                </div>
            </li>





        </ul>
        <!-- Sidebar -->
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- TopBar -->
                <!-- TopBar -->
                <nav class="navbar navbar-expand navbar-light bg-navbar topbar mb-4 static-top">
                    <button id="sidebarToggleTop" class="btn btn-default rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">


                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                {{-- Cek apakah pengguna memiliki foto di database --}}
                                <img src="{{ asset(Auth::check() && Auth::user()->foto ? Auth::user()->foto : 'asset/img/user/user.png') }}"
                                    alt="Foto Pengguna" style="max-width: 50px; max-height: 50px; border-radius: 50%;">
                                <div class="ml-2 mt-4 d-none d-lg-inline text-white small">
                                    @if (Auth::check())
                                        {{-- Mengecek apakah pengguna sudah login --}}
                                        <span style="font-weight: bold; color: white;">{{ Auth::user()->name }}</span>
                                        {{-- Tampilkan nama pengguna --}}
                                        <ul class="list-group list-group-flush" style="background-color: transparent;">
                                            @if (Auth::user()->role == 'teknisi')
                                                <li class="list-group-item"
                                                    style="background-color: transparent; border: none; color: white; font-weight: bold;">

                                                </li>
                                            @endif

                                            @if (Auth::user()->role == 'admin')
                                                <li class="list-group-item"
                                                    style="background-color: transparent; border: none; color: white; font-weight: bold;">

                                                </li>
                                            @endif

                                            @if (Auth::user()->role == 'superadmin')
                                                <li class="list-group-item"
                                                    style="background-color: transparent; border: none; color: white; font-weight: bold;">

                                                </li>
                                            @endif
                                        </ul>
                                    @else
                                        <script>
                                            window.location.href = "{{ route('login') }}";
                                        </script>
                                    @endif

                                </div>
                            </a>


                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>

                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="/logout">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>




                <!-- Topbar -->
                <div class="mb-5">

                </div>
                <!-- Container Fluid-->
                @yield('konten')
                <!-- Footer -->
            </div>
        </div>

        <!-- Scroll to top -->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <script src="{{ asset('template2/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('template2/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('template2/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
        <script src="{{ asset('template2/js/ruang-admin.min.js') }}"></script>
        <script src="{{ asset('template2/vendor/chart.js/Chart.min.js') }}"></script>
        <script src="{{ asset('template2/js/demo/chart-area-demo.js') }}"></script>

        <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="{{ asset('js/script.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

        <!-- Bootstrap 5 CSS -->

        <!-- Bootstrap 5 JS Bundle (termasuk Popper.js) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


    </div>


</body>

</html>
