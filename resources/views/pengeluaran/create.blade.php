@extends($layout)

@section('konten')
    <div class="container mt-4">
        <h6 class="text text-center text-black mt-3"> Tambah Data Pengeluaran</h6>
        <form action="{{ route('pengeluaran.store') }}" method="POST">
            @csrf
            <!-- Input jumlah -->
            <label for="jumlah" class=" mt-2">Jumlah:</label>
            <input type="number" name="jumlah" required class="form-control">

            <!-- Input keterangan -->
            <label for="keterangan" class=" mt-2">Keterangan:</label>
            <input type="text" name="keterangan" required class="form-control"> <br>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        </form>


    </div>
@endsection
