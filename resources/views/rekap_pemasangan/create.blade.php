@extends($layout)

@section('konten')
    <div class="container mt-4">
        <h6 class="text text-center text-black mt-3"> Tambah Data rekap pemasangan</h6>
        <form action="{{ route('rekap_pemasangan.store') }}" method="POST">
            @csrf
            <!-- Input ID Pelanggan -->
            <label for="id_plg" class=" mt-2">ID Pelanggan :</label>
            <input type="text" name="id_plg" required class="form-control">

            <label for="nik" class=" mt-2">KTP :</label>
            <input type="text" name="nik" required class="form-control">

            <!-- Input keterangan -->
            <label for="nama" class=" mt-2">Nama :</label>
            <input type="text" name="nama" required class="form-control">

            <!-- Input jumlah -->
            <label for="alamat" class=" mt-2">Alamat :</label>
            <input type="text" name="alamat" required class="form-control">

            <!-- Input keterangan -->
            <label for="no_telpon" class=" mt-2">NO Telepon:</label>
            <input type="text" name="no_telpon" required class="form-control">


            <label for="" class=" mt-2">Paket</label>
            <select name="paket_plg" id="paket_plg" class="form-control" onchange="setHargaPaket()">
                <option value="" disabled selected>Pilih Paket</option>
                <option value="1">Paket 1 - Rp 125.000</option>
                <option value="2">Paket 2 - Rp 165.000</option>
                <option value="3">Paket 3 - Rp 205.000</option>
                <option value="4">Paket 4 - Rp 305.000</option>
                <option value="5">Paket 5 - Rp 120.000</option>
                <option value="6">Paket 6 - Rp 175.000</option>
            </select>
            <div class="invalid-feedback" id="paket_plgError">Field Paket tidak boleh kosong.</div>

            <label for="" class=" mt-2">Harga Paket</label>
            <input type="text" name="harga_paket" id="harga_paket" class="form-control " readonly>

            <!-- Input keterangan -->
            <label for="tgl_pengajuan" class=" mt-2">Tanggal Pengajuan:</label>
            <input type="date" name="tgl_pengajuan" required class="form-control">

            <label for="tgl_aktivasi" class=" mt-2">Tanggal Aktivasi :</label>
            <input type="date" name="tgl_aktivasi" required class="form-control">

            <!-- Input jumlah -->
            <label for="registrasi" class=" mt-2">Registrasi :</label>
            <input type="text" name="registrasi" required class="form-control">
            <!-- Input ID odp -->
            <label for="odp" class=" mt-2">odp:</label>
            <input type="text" name="odp" required class="form-control">

            <!-- Input ID Keterangan -->
            <label for="longitude" class=" mt-2">longitude :</label>
            <input type="text" name="longitude" required class="form-control">

            <!-- Input ID latitude -->
            <label for="latitude" class=" mt-2">latitude :</label>
            <input type="text" name="latitude" required class="form-control">


            <!-- Input keterangan -->
            <label for="marketing" class=" mt-2">Marketing:</label>
            <input type="text" name="marketing" required class="form-control">

            <!-- Input ID Keterangan -->
            <label for="keterangan_plg" class=" mt-2"> Keterangan :</label>
            <input type="text" name="keterangan_plg" required class="form-control"> <br>


            <!-- Submit button -->
            <button type="submit" class="btn btn-primary btn-sm">Simpan</button> <br><br>
        </form>

    </div>

    <script>
        function setHargaPaket() {
            const paketPlg = document.getElementById('paket_plg').value;
            const hargaPaket = document.getElementById('harga_paket');

            const hargaList = {
                '1': '125000',
                '2': '165000',
                '3': '205000',
                '4': '305000',
                '5': '120000',
                '6': '175000'
            };

            // Set harga sesuai pilihan paket
            if (paketPlg in hargaList) {
                hargaPaket.value = hargaList[paketPlg];
            } else {
                hargaPaket.value = ''; // Kosongkan jika tidak ada paket yang dipilih
            }
        }
    </script>
@endsection
