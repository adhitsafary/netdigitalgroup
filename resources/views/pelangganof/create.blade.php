@extends($layout)

@section('konten')
    <div class="container mt-4">
        <h6 class="text text-center text-black mt-3"> Tambah Data Pelanggan</h6>
        <form action="{{ route('pelanggan.store') }}" method="POST">
            @csrf
            <label for="">ID</label>
            <input type="text" name="id_plg" class="form-control mt-2">
            <label for="">Nama Pelanggan</label>
            <input type="text" name="nama_plg" class="form-control mt-2">
            <label for="">Alamat</label>
            <input type="text" name="alamat_plg" class="form-control mt-2">
            <label for="">No Telpon</label>
            <input type="text" name="no_telepon_plg" class="form-control mt-2">
            <label for="">Aktivasi</label>
            <input type="date" name="aktivasi_plg" class="form-control mt-2">
            <label for="">Paket</label>
            <input type="text" name="paket_plg" class="form-control mt-2">
            <label for="">Harga Paket</label>
            <input type="text" name="harga_paket" class="form-control mt-2">
            <label for="">ODP</label>
            <input type="text" name="odp" class="form-control mt-2">
            <label for="">Latitude</label>
            <input type="text" name="latitude" class="form-control mt-2">
            <label for="">Longitude</label>
            <input type="text" name="longitude" class="form-control mt-2"> <br>
            <label for="">Keterangan</label>
            <input type="text" name="keterangan_plg" class="form-control mt-2"> <br>
    
            <button class="btn btn-primary btn-sm">Simpan</button>
        </form>
    </div>
   
@endsection
