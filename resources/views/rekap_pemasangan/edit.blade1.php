@extends($layout)

@section('konten')

<div class="container mt-4">
    <h6 class="text text-center text-black mt-3"> Edit Data rekap pemasangan : {{$rekap_pemasangan -> nama}} </h6>
    <form action="{{route('rekap_pemasangan.update', $rekap_pemasangan->id)}}" method="POST">
        @csrf

        <label for="">ID Pelanggan</label>
        <input type="text" name="nik" value="{{$rekap_pemasangan -> nik}}" class="form-control mt-2">
        <label for="">nik</label>
        <input type="text" name="nik" value="{{$rekap_pemasangan -> nik}}" class="form-control mt-2">
        <label for="">Nama</label>
        <input type="text" name="nama" value="{{$rekap_pemasangan -> nama}}" class="form-control mt-2">
        <label for="">Alamat</label>
        <input type="text" name="alamat" value="{{$rekap_pemasangan -> alamat}}" class="form-control mt-2">

        <label for="">No Telepon</label>
        <input type="text" name="no_telpon" value="{{$rekap_pemasangan -> no_telpon}}" class="form-control mt-2">
        <label for="">Aktivasi</label>
        <input type="date" name="tgl_aktivasi" value="{{$rekap_pemasangan -> tgl_aktivasi}}" class="form-control mt-2">

        <label for="">Paket</label>
        <input type="text" name="paket_plg" value="{{$rekap_pemasangan -> paket_plg}}" class="form-control mt-2">
        <label for="">Nominal</label>
        <input type="text" name="harga_paket" value="{{$rekap_pemasangan -> harga_paket}}" class="form-control mt-2">

        <label for="">Jatuh Tempo</label>
        <input type="text" name="jt" value="{{$rekap_pemasangan -> jt}}" class="form-control mt-2">
        <label for="">Status</label>
        <input type="text" name="status" value="{{$rekap_pemasangan -> status}}" class="form-control mt-2">

        <label for="">Pengajuan</label>
        <input type="date" name="tgl_pengajuan" value="{{$rekap_pemasangan -> tgl_pengajuan}}" class="form-control mt-2">
        <label for="">Registrasi</label>
        <input type="text" name="registrasi" value="{{$rekap_pemasangan -> registrasi}}" class="form-control mt-2">

        <label for="">Marketing</label>
        <input type="text" name="marketing" value="{{$rekap_pemasangan -> marketing}}" class="form-control mt-2"> <br>


        <button class="btn btn-primary btn-sm">Simpan</button> <br><br>
    </form>
</div>



@endsection

