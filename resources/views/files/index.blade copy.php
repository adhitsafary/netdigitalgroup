@extends($layout)

@section('konten')
<div class="container">
    <h1 class="my-4">Uploaded Files</h1>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('file.create') }}" class="btn btn-primary mb-3">Upload New Files</a>

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>File Name</th>
                <th>File Size</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($files as $file)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $file->file_name }}</td>
                    <td>{{ $file->file_size }} bytes</td>
                    <td>
                        <a href="{{ Storage::url($file->file_path) }}" class="btn btn-primary">Lihat</a>
                        <a href="{{ route('file.download', $file->id) }}" class="btn btn-success">Download</a>
                        <a href="{{ route('file.edit', $file->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('file.destroy', $file->id) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
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
