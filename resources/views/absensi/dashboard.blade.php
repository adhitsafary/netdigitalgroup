@extends($layout)

@section('konten')
<div class="container">
    <h4 class="my-4">Data Absensi</h4>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif


    <table class="table table-bordered table-responsive"
        style="color: black; width: 100%; font-size: 0.85em; table-layout: fixed;">
        <thead class="table table-primary" style="color: black;">
            <tr class="font font-weight-bold">
                <th style="width: 1%; padding: 1px;">No</th>
                <th style="width: 1%; padding: 1px">Nama User</th>
                <th style="width: 1%; padding: 1px">Jam Masuk</th>
                <th style="width: 1%; padding: 1px">Jam Pulang</th>
                <th style="width: 1%; padding: 1px">Titik Lokasi</th>
                <th style="width: 1%; padding: 1px">Foto</th>
                <th style="width: 1%; padding: 1px;">Delete</th>
            </tr>
        </thead>
        <tbody>
            @foreach($absensi as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->user->name }}</td>
                <td>{{ $item->jam_masuk }}</td>
                <td>{{ $item->jam_pulang }}</td>
                <td>{{ $item->titik_lokasi }}</td>
                <td>
                    @if($item->foto)
                    <img src="{{ asset($item->foto) }}" alt="Foto Absensi" width="50">
                    @else
                    <span>No Foto</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('absensi.destroy', $item->id) }}" method="POST"
                        class="d-inline-block">
                        @csrf
                        <a href="#"
                            onclick="if(confirm('Yakin ingin menghapus data ini?')) { this.closest('form').submit(); return false; }"
                            style="display: inline-block;">
                            <img src="{{ asset('asset/img/icon/delete.png') }}"
                                style="height: 35px; width: 35px;" alt="Hapus">
                        </a>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection