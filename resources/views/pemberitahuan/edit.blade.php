@extends($layout)

@section('konten')
    <div class="container">
        <h1 class="my-4">Edit Pemberitahuan</h1>

        <form action="{{ route('pemberitahuan.update', $pemberitahuan->id) }}" method="POST">
            @csrf
            @method('POST')

            <div class="mb-3">
                <label for="nama" class="form-label">Nama</label>
                <input type="text" name="nama" class="form-control" id="nama" value="{{ $pemberitahuan->nama }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="pesan" class="form-label">Pesan</label>
                <textarea name="pesan" class="form-control" id="pesan" required>{{ $pemberitahuan->pesan }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('pemberitahuan.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
@endsection
