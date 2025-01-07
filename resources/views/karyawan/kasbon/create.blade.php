@extends('superadmin.layout_superadmin')

@section('konten')
    <div class="container mt-4">
        <h6 class="text text-center text-black mt-3"> Tambah Data Karyawan</h6>
        <form action="{{ route('kasbon.store') }}" method="POST">
            @csrf
            <!-- Input hidden untuk id_karyawan -->
            <input type="hidden" name="id_karyawan" value="{{ $karyawan->id }}"  class="form-control">

            <!-- Nama karyawan -->
            <label for="nama" class=" mt-2">Nama Karyawan:</label>
            <input type="text" name="nama" value="{{ $karyawan->nama }}" readonly class="form-control">

            <!-- Input jumlah -->
            <label for="jumlah" class=" mt-2">Jumlah:</label>
            <input type="number" name="jumlah" required class="form-control">

            <!-- Input tanggal -->
            <label for="tanggal" class=" mt-2">Tanggal:</label>
            <input type="date" name="tanggal" required class="form-control ">

            <!-- Input keterangan -->
            <label for="keterangan" class=" mt-2">Keterangan:</label>
            <input type="text" name="keterangan" required class="form-control"> <br>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        </form>


    </div>
@endsection
