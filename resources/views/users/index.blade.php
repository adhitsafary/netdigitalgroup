@extends($layout)

@section('konten')
    <div class="container mb-4" style="color: black;">
        <form action="{{ route('pemasukan.index') }}" method="GET" class="form-inline mb-4 ">
            <div class="input-group">
                <input style="color: black;" type="text" name="search" id="search" class="form-control"
                    value="{{ request('search') }}" placeholder="Pencarian">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary">Cari</button>
                </div>
            </div>
        </form>

        <a href="{{ route('users.create') }}" class="btn btn-success">Buat User Baru</a>

        <div style="display: flex; justify-content: center;" class="mb-3">
            <h5 style="color: black;" class="font font-weight-bold">Data User Login</h5>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered" style="color: black;">
            <thead class="table table-primary" style="color: black;">
                <tr>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="font font-weight-bold" style="color: black">
                        <td>
                            <img src="{{ asset($user->foto) }}" alt="Foto Pengguna"
                                style="max-width: 60px; max-height: 60px; border-radius: 50%;">
                        </td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ ucfirst($user->role) }}</td>
                        <td>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Apakah Anda yakin ingin menghapus user ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
