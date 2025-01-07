@extends($layout)

@section('konten')
    <div class="container mt-4">
        <form action="{{ route('perbaikan.update', $perbaikan->id) }}" method="POST">
            @csrf<br>
            <h6>Edit Data Perbaikan</h6><br>
            <label for="">ID</label>
            <input type="text" name="id_plg" value="{{ $perbaikan->id_plg }}" class="form-control mt-2">
            <label for="">Nama Pelanggan</label>
            <input type="text" name="nama_plg" value="{{ $perbaikan->nama_plg }}" class="form-control mt-2">
            <label for="">Alamat</label>
            <input type="text" name="alamat_plg" value="{{ $perbaikan->alamat_plg }}" class="form-control mt-2">
            <label for="">No Telpon</label>
            <input type="text" name="no_telepon_plg" value="{{ $perbaikan->no_telepon_plg }}" class="form-control mt-2">
            <label for="">Paket</label>
            <input type="text" name="paket_plg" value="{{ $perbaikan->paket_plg }}" class="form-control mt-2">
            <label for="">ODP</label>
            <input type="text" name="odp" value="{{ $perbaikan->odp }}" class="form-control mt-2">
            <label for="">Maps</label>
            <input type="text" name="maps" value="{{ $perbaikan->maps }}" class="form-control mt-2">
            <label for="">Teknisi</label>

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
            <button class="btn btn-primary btn-sm">Simpan</button>
        </form>
    </div>
@endsection
