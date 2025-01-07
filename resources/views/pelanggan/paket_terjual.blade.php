@extends($layout)

@section('konten')
    <div class="container">

        <div class="col-xl-4 col-lg-5">
            <div class="card mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Paket Terjual</h6>
                </div>
                <div class="card-body">
                    @foreach ($paketTop5 as $paket)
                        @php
                            // Hitung persentase user dari total user
                            $percentage = ($paket->total_user / $totalUsers) * 100;
                        @endphp
                        <div class="mb-3">
                            <div class="small text-gray-500">Harga {{ number_format($paket->harga_paket, 0, ',', '.') }}
                                <div class="small float-right"><b>{{ $paket->total_user }} User</b>
                                </div>
                            </div>
                            <div class="progress" style="height: 12px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $percentage }}%"
                                    aria-valuenow="{{ $paket->total_user }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    @endforeach

                    <div id="extraPaket" style="display:none;">
                        @foreach ($paketRemaining as $paket)
                            @php
                                // Hitung persentase user dari total user
                                $percentage = ($paket->total_user / $totalUsers) * 100;
                            @endphp
                            <div class="mb-3">
                                <div class="small text-gray-500">Paket {{ $paket->harga_paket }}
                                    <div class="small float-right"><b>{{ $paket->total_user }}
                                            User</b></div>
                                </div>
                                <div class="progress" style="height: 12px;">
                                    <div class="progress-bar bg-primary" role="progressbar"
                                        style="width: {{ $percentage }}%" aria-valuenow="{{ $paket->total_user }}"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-center">
                    <a class="m-0 small text-primary card-link" href="#" id="showMoreBtn">Lihat
                        Lebih Banyak <i class="fas fa-chevron-right"></i></a>
                </div>
            </div>
        </div>


    </div>
@endsection
