@extends($layout)

@section('konten')
    <div class="container mb-4">
        <h5 class="text-center text-black mb-3 font-weight-bold " style="color: black">Tambah Data Pemasangan Baru</h5>
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('psb.store') }}" method="POST" class="font font-weight-bold" style="color: black">
            @csrf



            <label for="id_plg">ID Pelanggan</label>
            <input type="text" id="id_plg" name="id_plg" class="form-control mb-2">

            <label for="id_plg">Nama</label>
            <input type="text" id="nama_plg" name="nama_plg" class="form-control mb-2">
            <label for="alamat_plg">Alamat</label>
            <input type="text" id="alamat_plg" name="alamat_plg" class="form-control mb-2">

            <label for="no_telepon_plg">No Telpon</label>
            <input type="text" id="no_telepon_plg" name="no_telepon_plg" class="form-control mb-2">

            <label for="paket_plg">Paket</label>
            <input type="text" id="paket_plg" name="paket_plg" class="form-control mb-2">

            <label for="odp">ODP</label>
            <input type="text" id="odp" name="odp" class="form-control mb-2">
            <div class="invalid-feedback" id="odpError">Field ODP tidak boleh kosong, bila tidak ada tulis " 0 ".</div>

            <label for="maps">Maps</label>
            <input type="text" id="maps" name="maps" class="form-control mb-2">
            <div class="invalid-feedback" id="mapsError">Field Maps tidak boleh kosong, bila tidak ada tulis " 0 ".</div>

            <div class="form-group">
                <label for="teknisi">Teknisi</label>
                <select id="teknisi" name="teknisi" class="form-control mt-2">
                    <option value="">Pilih Teknisi</option> <!-- Opsi kosong untuk tidak memilih teknisi -->
                    <option value="Tim 1 Deden - Agis">Tim 1 Deden - Agis</option>
                    <option value="Tim 2 Mursidi - Dindin">Tim 2 Mursidi - Dindin</option>
                    <option value="Tim 3 Isep - Indra">Tim 3 Isep - Indra</option>
                </select>
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan</label>
                <select id="keterangan" name="keterangan" class="form-control mt-2">
                    <option value="">Pilih Gangguan</option>
                    <option value="Modem error / matot">Modem error / matot</option>
                    <option value="Los / modem merah">Los / modem merah</option>
                    <option value="PSB">PSB</option>
                </select>
                <div class="invalid-feedback" id="keteranganError">Field Keterangan tidak boleh kosong, bila tidak ada tulis
                    "0".</div>
            </div>




            <br>
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button> <br><br><br><br>
        </form>
    @endsection
