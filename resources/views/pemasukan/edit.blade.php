@extends($layout)

@section('konten')

<div class="container mt-4">
    <h6 class="text text-center text-black mt-3"> Edit Data Pemasukan : {{$pemasukan -> nama}} </h6>
    <form action="{{route('pemasukan.update', $pemasukan->id)}}" method="POST">
        @csrf

        <label for="">Jumlah</label>
        <input type="text" name="jumlah" value="{{$pemasukan -> jumlah}}" class="form-control mt-2">

        <label for="">Keterangan</label>
        <input type="text" name="keterangan" value="{{$pemasukan -> keterangan}}" class="form-control mt-2">

        <button class="btn btn-primary btn-sm">Simpan</button>
    </form>
</div>



@endsection

