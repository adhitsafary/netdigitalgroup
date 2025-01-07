@extends($layout)

@section('konten')
    <div class="container">
        <h1 class="my-4">NDG Drive</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <a href="{{ route('file.create') }}" class="btn btn-danger mb-3">Upload File baru</a>

        <div class="d-flex align-items-center justify-content-between mt-2">
            <form action="{{ route('file.index') }}" method="GET" class="form-inline d-flex" style="color: black;">
                <div class="input-group" style="color: black;">
                    <input type="text" name="search" id="search" class="form-control font-weight-bold mr-2"
                        style="color: black;" value="{{ request('search') }}" placeholder="Pencarian">
                </div>
                <input type="date" id="updated_at" name="updated_at" value="{{ request()->get('updated_at') }}">
                <button type="submit" name="action" value="search" class="btn btn-danger ml-2">Cari</button>
            </form>
        </div>


        <div class="container">


            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>File</th>
                        <th>Ukuran</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($files as $file)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="d-flex align-items-center">
                                @if (in_array(pathinfo($file->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'svg']))
                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank">
                                        <img src="{{ Storage::url($file->file_path) }}" alt="Preview" class="img-thumbnail"
                                            style="width: 50px; height: 50px; margin-right: 10px;">
                                    </a>
                                @else
                                    <a href="{{ Storage::url($file->file_path) }}" target="_blank">
                                        <img src="{{ asset('images/default-file-preview.png') }}" alt="No Preview"
                                            class="img-thumbnail" style="width: 50px; height: 50px; margin-right: 10px;">
                                    </a>
                                @endif
                                <a href="{{ Storage::url($file->file_path) }}" target="_blank">{{ $file->file_name }}</a>
                            </td>
                            <td>{{ number_format($file->file_size / 1048576, 2) }} MB</td>

                            <!-- Dropdown Action Button -->
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-danger dropdown-toggle" type="button"
                                        id="actionDropdown{{ $file->id }}" data-bs-toggle="dropdown"
                                        aria-expanded="false">

                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="actionDropdown{{ $file->id }}">
                                        <!-- Menghapus tindakan "Lihat" karena sudah langsung bisa diklik pada nama file -->
                                        <li><a class="dropdown-item"
                                                href="{{ route('file.download', $file->id) }}">Download</a></li>
                                        <li><a class="dropdown-item" href="{{ route('file.edit', $file->id) }}">Edit</a>
                                        </li>
                                        <li>
                                            <form action="{{ route('file.destroy', $file->id) }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">Delete</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No files uploaded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>
    @endsection
