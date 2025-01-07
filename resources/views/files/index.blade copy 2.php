@extends($layout)

@section('konten')
<div class="container">
    <h1 class="my-4">Uploaded Files</h1>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('file.create') }}" class="btn btn-primary mb-3">Upload File Baru</a>

    <div class="row">
        @forelse($files as $file)
            <div class="col-md-2 mb-4">
                <div class="card h-100">
                    <!-- Cek apakah file adalah gambar untuk pratinjau -->
                    @if(in_array(pathinfo($file->file_path, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif', 'svg']))
                        <img src="{{ Storage::url($file->file_path) }}" class="card-img-top img-fluid small-preview" alt="{{ $file->file_name }}">
                    @else
                        <!-- Tampilkan pratinjau default jika bukan gambar -->
                        <img src="{{ asset('images/default-file-preview.png') }}" class="card-img-top img-fluid small-preview" alt="File Preview">
                        <div class="card-body">
                            <p class="text-center text-muted">Tidak dapat ditampilkan</p>
                        </div>
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $file->file_name }}</h5>
                        <p class="card-text">Size: {{ $file->file_size }} bytes</p>
                        <a href="{{ Storage::url($file->file_path) }}" class="btn btn-success btn-sm" target="_blank">Lihat</a>
                        <a href="{{ route('file.download', $file->id) }}" class="btn btn-primary btn-sm">Download</a>
                        <a href="{{ route('file.edit', $file->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('file.destroy', $file->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <p class="text-center">No files uploaded yet.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    /* Ukuran preview yang lebih kecil */
    .small-preview {
        max-height: 150px;
        object-fit: contain;
    }

    .card-body {
        padding: 10px;
    }

    .card-title {
        font-size: 14px;
    }

    .card-text {
        font-size: 12px;
    }
</style>
@endsection
