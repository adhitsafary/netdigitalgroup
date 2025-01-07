<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Tanggal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Ubah Tanggal Created_at</h1>

        <!-- Tampilkan pesan sukses -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama</th>
                    <th>Created At</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $record)
                    <tr>
                        <td>{{ $record->id }}</td>
                        <td>{{ $record->nama }}</td>
                        <td>{{ $record->created_at }}</td>
                        <td>
                            <!-- Tombol untuk mengubah tanggal -->
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalUbahTanggal" 
                                data-id="{{ $record->id }}" data-created-at="{{ $record->created_at }}">
                                Ubah Tanggal
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modal untuk mengubah tanggal -->
    <div class="modal fade" id="modalUbahTanggal" tabindex="-1" aria-labelledby="modalUbahTanggalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('ubah-tanggal.update') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalUbahTanggalLabel">Ubah Tanggal</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="recordId">
                        <div class="mb-3">
                            <label for="created_at" class="form-label">Tanggal Baru</label>
                            <input type="date" class="form-control" id="created_at" name="created_at" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Isi modal dengan data dari tombol
        const modal = document.getElementById('modalUbahTanggal');
        modal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const createdAt = button.getAttribute('data-created-at');

            const modalIdInput = modal.querySelector('#recordId');
            const modalDateInput = modal.querySelector('#created_at');

            modalIdInput.value = id;
            modalDateInput.value = createdAt;
        });
    </script>
</body>
</html>
