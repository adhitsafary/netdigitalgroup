@extends($layout)

@section('konten')
<div class="container">
    <h1 class="my-4">Pemberitahuan</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('pemberitahuan.create') }}" class="btn btn-primary mb-3">Tambah Pemberitahuan</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Pesan</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pemberitahuan as $item)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item->nama }}</td>
                    <td>{{ $item->pesan }}</td>
                    <td>{{ $item->updated_at }}</td>
                    <td>
                        <a href="{{ route('pemberitahuan.edit', $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('pemberitahuan.destroy', $item->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada pemberitahuan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
