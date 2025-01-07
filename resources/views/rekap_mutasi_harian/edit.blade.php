@extends('superadmin.layout_superadmin')

@section('konten')

<div class="container mt-4">
    <h6 class="text text-center text-black mt-3"> Edit Data kasbon : {{$kasbon -> nama}} </h6>
    <form action="{{route('kasbon.update', $kasbon->id)}}" method="POST">
        @csrf

        <label for="">Nama</label>
        <input type="text" name="nama" value="{{$kasbon -> nama}}"  class="form-control mt-2">
        <label for="">Jumlah</label>
        <input type="text" name="jumlah" value="{{$kasbon -> jumlah}}" class="form-control mt-2">
        <label for="">Tanggal</label>
        <input type="text" name="tanggal" value="{{$kasbon -> tanggal}}" class="form-control mt-2">
        <label for="">Keterangan</label>
        <input type="text" name="tanggal" value="{{$kasbon -> keterangan}}" class="form-control mt-2">

        <button class="btn btn-primary btn-sm">Simpan</button>
    </form>
</div>



@endsection

