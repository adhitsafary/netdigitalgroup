@extends($layout)

@section('konten')

<div class="container mt-4">
    <h6 class="text text-center text-black mt-3"> Edit Data Pengeluaran : {{$pengeluaran -> nama}} </h6>
    <form action="{{route('pengeluaran.update', $pengeluaran->id)}}" method="POST">
        @csrf

        <label for="">Jumlah</label>
        <input type="text" name="jumlah" value="{{$pengeluaran -> jumlah}}" class="form-control mt-2">

        <label for="">Keterangan</label>
        <input type="text" name="keterangan" value="{{$pengeluaran -> keterangan}}" class="form-control mt-2">

        <button class="btn btn-primary btn-sm">Simpan</button>
    </form>
</div>



@endsection

