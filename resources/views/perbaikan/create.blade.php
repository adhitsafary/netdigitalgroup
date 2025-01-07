@extends($layout)

@section('konten')
    <div class="container mt-4">
        <h6 class="text-center text-black mt-3">Tambah Data Perbaikan</h6>
        <form id="perbaikanForm" action="{{ route('perbaikan.store') }}" method="POST">
            @csrf

            <label for="nama_plg">Cari Nama Pelanggan</label>
            <select id="nama_plg_select" name="nama_plg_select" class="form-control mt-2"></select> <br><br>

            <!-- Input tersembunyi untuk nama pelanggan -->
            <input type="hidden" id="nama_plg" name="nama_plg">

            <label for="id_plg">ID Pelanggan</label>
            <input type="text" id="id_plg" name="id_plg" class="form-control mt-2" readonly>

            <label for="alamat_plg">Alamat</label>
            <input type="text" id="alamat_plg" name="alamat_plg" class="form-control mt-2" readonly>

            <label for="no_telepon_plg">No Telpon</label>
            <input type="text" id="no_telepon_plg" name="no_telepon_plg" class="form-control mt-2" readonly>

            <label for="paket_plg">Paket</label>
            <input type="text" id="paket_plg" name="paket_plg" class="form-control mt-2" readonly>

            <label for="odp">ODP</label>
            <input type="text" id="odp" name="odp" class="form-control mt-2">
            <div class="invalid-feedback" id="odpError">Field ODP tidak boleh kosong, bila tidak ada tulis " 0 ".</div>

            <label for="maps">Maps</label>
            <input type="text" id="maps" name="maps" class="form-control mt-2">
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


            <button type="submit" class="btn btn-primary btn-sm">Simpan</button> <br> <br>
        </form>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#nama_plg_select').select2({
                    placeholder: 'Cari nama pelanggan',
                    ajax: {
                        url: '/search-pelanggan',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                search: params.term
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        cache: true
                    }
                });

                $('#nama_plg_select').on('select2:select', function(e) {
                    var data = e.params.data;

                    $.ajax({
                        url: '/get-pelanggan/' + data.id,
                        type: 'GET',
                        dataType: 'json',
                        success: function(pelanggan) {
                            if (pelanggan) { // Pastikan data pelanggan tidak kosong
                                $('#id_plg').val(pelanggan.id_plg);
                                $('#alamat_plg').val(pelanggan.alamat_plg);
                                $('#no_telepon_plg').val(pelanggan.no_telepon_plg);
                                $('#paket_plg').val(pelanggan.paket_plg);
                                $('#odp').val(pelanggan.odp);
                                $('#maps').val(pelanggan.maps);
                                $('#nama_plg').val(data
                                    .text); // Simpan nama pelanggan ke input tersembunyi
                            } else {
                                // Tampilkan pesan kesalahan jika tidak ada data pelanggan
                                alert('Data pelanggan tidak ditemukan.');
                            }
                        },
                        error: function() {
                            alert('Terjadi kesalahan saat mengambil data pelanggan.');
                        }
                    });
                });

                $('#perbaikanForm').on('submit', function(e) {
                    var isValid = true;

                    // Validasi field teknisi
                    var teknisi = $('#teknisi').val();
                    if (!teknisi) { // Ubah dari === "" ke !teknisi untuk memeriksa kebenaran
                        $('#teknisi').addClass('is-invalid');
                        $('#teknisiError').show();
                        isValid = false;
                    } else {
                        $('#teknisi').removeClass('is-invalid');
                        $('#teknisiError').hide();
                    }

                    // Validasi field keterangan
                    var keterangan = $('#keterangan').val();
                    if (!keterangan) { // Ubah dari === "" ke !keterangan untuk memeriksa kebenaran
                        $('#keterangan').addClass('is-invalid');
                        $('#keteranganError').show();
                        isValid = false;
                    } else {
                        $('#keterangan').removeClass('is-invalid');
                        $('#keteranganError').hide();
                    }

                    // Validasi field odp
                    var odp = $('#odp').val();
                    if (!odp) { // Ubah dari === "" ke !odp untuk memeriksa kebenaran
                        $('#odp').addClass('is-invalid');
                        $('#odpError').show();
                        isValid = false;
                    } else {
                        $('#odp').removeClass('is-invalid');
                        $('#odpError').hide();
                    }

                    // Validasi field maps
                    var maps = $('#maps').val();
                    if (!maps) { // Ubah dari === "" ke !maps untuk memeriksa kebenaran
                        $('#maps').addClass('is-invalid');
                        $('#mapsError').show();
                        isValid = false;
                    } else {
                        $('#maps').removeClass('is-invalid');
                        $('#mapsError').hide();
                    }

                    if (!isValid) {
                        e.preventDefault(); // Mencegah pengiriman form jika tidak valid
                    }
                });
            });
        </script>
    @endsection
