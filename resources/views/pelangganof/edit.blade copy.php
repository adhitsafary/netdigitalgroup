@extends($layout)

@section('konten')

<div class="container mt-4">
    <h6 class="text text-center text-black mt-3"> Edit Pelanggan Off</h6>
    <form action="{{route('pelangganof.update', $pelangganof->id)}}" method="POST">
        @csrf
        <label for="">ID</label>
        <input type="text" name="id_plg" value="{{$pelangganof -> id_plg}}" class="form-control mt-2">
        <label for="">Nama Pelanggan</label>
        <input type="text" name="nama_plg" value="{{$pelangganof -> nama_plg}}" class="form-control mt-2">
        <label for="">Alamat</label>
        <input type="text" name="alamat_plg" value="{{$pelangganof -> alamat_plg}}" class="form-control mt-2">
        <label for="">No Telpon</label>
        <input type="text" name="no_telepon_plg" value="{{$pelangganof -> no_telepon_plg}}" class="form-control mt-2">
        <label for="">Aktivasi</label>
        <input type="text" name="aktivasi_plg" value="{{$pelangganof -> aktivasi_plg}}" class="form-control mt-2">
        <label for="">Paket</label>
        <input type="text" name="paket_plg" value="{{$pelangganof -> paket_plg}}" class="form-control mt-2">
        <label for="">Harga Paket</label>
        <input type="text" name="harga_paket" value="{{$pelangganof -> harga_paket}}" class="form-control mt-2">
        <label for="">Status</label>
        <input type="text" name="status_plg" value="{{$pelangganof -> status_plg}}" class="form-control mt-2">
        <label for="">Keterangan</label>
        <input type="text" name="keterangan_plg" value="{{$pelangganof -> keterangan_plg}}" class="form-control mt-2">

        <button class="btn btn-primary btn-sm">Simpan</button>
    </form>
</div>



@endsection