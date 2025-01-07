@extends($layout)

@section('konten')
    <div class=" p-5 ">

        <!--
        <div>
            <div class="ml-2 d-none d-lg-inline text-black text-right">
                <ul class="list-group list-group-flush">
                    @if (Auth::user()->role == 'teknisi')
                        <li class="list-group-item">Menu Teknisi</li>
                    @endif
                    @if (Auth::user()->role == 'admin')
                        <li class="list-group-item">Menu Admin</li>
                    @endif
                    @if (Auth::user()->role == 'superadmin')
                        <li class="list-group-item">Menu SuperAdmin</li>
                    @endif
                </ul>
            </div>
        </div> -->

        <!-- Form Filter dan Pencarian -->
        <div class="row mb-4">
            <div class="col-md-9">
                <form action="{{ route('teknisi.index') }}" method="GET" class="form-inline">
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control"
                            value="{{ request('search') }}" placeholder="Pencarian">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">Cari</button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-3 text-right">
                <div class="d-flex justify-content-end gap-2">


                    <a class="mt-2 btn btn-danger btn-sm mb-2" href="/logout">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Logout
                    </a>

                </div>
            </div>
        </div>


        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!--table-responsive -->

        <table class="table table-bordered " style="color: black;">
            <thead class="table table-danger" style="color: black;">
                <tr>
                    <th>No</th>
                    <th>ID Pel</th>
                    <th>Nama Pel</th>
                    <th>Alamat</th>
                    <th>No Hp</th>
                    <th>Paket</th>
                    <th>Odp</th>
                    <th>Maps</th>
                    <th>Teknisi</th>
                    <th>Keterangan</th>
                    <th>
                        <!-- Link untuk sorting -->
                        <a
                            href="{{ route('teknisi.index', array_merge(request()->except('sort'), ['sort' => $sort === 'asc' ? 'desc' : 'asc'])) }}">
                            Tanggal
                            @if ($sort === 'asc')
                                &uarr; <!-- Icon untuk sorting ascending -->
                            @else
                                &darr; <!-- Icon untuk sorting descending -->
                            @endif
                        </a>
                    </th>
                    <th>Status</th>
                    <th>Selesai</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($perbaikan as $no => $item)
                    <tr>
                        <td>{{ $no + 1 }}</td>
                        <td>{{ $item->id_plg }}</td>
                        <td>{{ $item->nama_plg }}</td>
                        <td>{{ $item->alamat_plg }}</td>
                        <td>{{ $item->no_telepon_plg }}</td>
                        <td>{{ $item->paket_plg }}</td>
                        <td>{{ $item->odp }}</td>
                        <td>{{ $item->maps }}</td>
                        <td>{{ $item->teknisi }}</td>
                        <td>{{ $item->keterangan }}</td>
                        <td>{{ $item->created_at }}</td>

                        <td>{{ ucfirst($item->status) }}</td> <!-- Menampilkan status -->
                        <td>
                            @if ($item->status == 'Proses')
                                <!-- Hanya tampilkan tombol jika statusnya Proses -->
                                <form action="{{ route('perbaikan.selesai', $item->id) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-success btn-sm">Selesai</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center">Tidak ada data ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
