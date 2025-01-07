@extends($layout)

@section('konten')
    <div class="container">
        <h1 class="my-4">Edit File</h1>

        <form action="{{ route('file.update', $file->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST') <!-- Pastikan menggunakan POST method -->

            <!-- Input untuk mengubah nama file -->
            <div class="mb-3">
                <label for="file_name" class="form-label">Edit File Name</label>
                <input type="text" name="file_name" class="form-control" id="file_name" value="{{ $file->file_name }}">
            </div>

            <!-- Input untuk mengubah file jika diperlukan -->
            <div class="mb-3">
                <label for="file" class="form-label">Replace File (Optional)</label>
                <input type="file" name="file" class="form-control" id="file">
            </div>

            <button type="submit" class="btn btn-warning">Update</button>
        </form>
    </div>
@endsection
