@extends('superadmin.layout_superadmin')

@section('konten')
    <div class="container mt-4">
        <h6 class="text text-center text-black mt-3"> Edit Data Karyawan : {{ $karyawan->nama }} </h6>
        <form action="{{ route('karyawan.update', $karyawan->id) }}" method="POST">
            @csrf

            <label for="">Nama Karyawan</label>
            <input type="text" name="nama" value="{{ $karyawan->nama }}" class="form-control mt-2">
            <label for="">KTP</label>
            <input type="text" name="ktp" value="{{ $karyawan->ktp }}" class="form-control mt-2">
            <label for="">Alamat</label>
            <input type="text" name="alamat" value="{{ $karyawan->alamat }}" class="form-control mt-2">
            <label for="">No Telepon</label>
            <input type="text" name="no_telepon" value="{{ $karyawan->no_telepon }}" class="form-control mt-2">
            <label for="">Posisi</label>
            <input type="text" name="posisi" value="{{ $karyawan->posisi }}" class="form-control mt-2">
            <label for="">Gaji</label>
            <input type="text" name="gaji" value="{{ $karyawan->gaji }}" class="form-control mt-2">
            <label for="">Tanggal Gajihan</label>
            <input type="date" name="tgl_gajihan" value="{{ $karyawan->tgl_gajihan }}" class="form-control mt-2">

            <label for="">Mulai kerja</label>
            <input type="date" name="mulai_kerja" value="{{ $karyawan->mulai_kerja }}" class="form-control mt-2">
            <label for="">Keterangan</label>
            <input type="text" name="keterangan" value="{{ $karyawan->keterangan }}" class="form-control mt-2"> <br>
            <div class="form-group">
                <label for="foto">Foto (Biarkan kosong jika tidak ingin mengganti):</label>
                <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*">
                @if ($karyawan->foto)
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $karyawan->foto) }}" alt="Foto Pengguna"
                            style="max-width: 100px; border-radius: 5px;">
                    </div>
                @endif
            </div>


            <button class="btn btn-primary btn-sm">Simpan</button>
        </form>
    </div>
@endsection
