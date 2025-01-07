@extends($layout)

@section('konten')
    <div class="container">
        <h1 class="my-4">Upload Data Baru</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('file.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
            @csrf

            <div id="fileInputs">
                <!-- Input untuk multi file upload tanpa batasan jenis file -->
                <div class="mb-3">
                    <label for="files" class="form-label">Pilih File</label>
                    <input type="file" name="files[]" class="form-control" id="files" multiple
                        onchange="handleFiles(this)">
                    <div id="fileNames"></div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Upload File</button>
        </form>
    </div>

    <script>
        function handleFiles(input) {
            const fileNamesDiv = document.getElementById('fileNames');
            fileNamesDiv.innerHTML = ''; // Kosongkan nama file sebelumnya

            // Iterasi melalui file yang dipilih
            Array.from(input.files).forEach((file, index) => {
                // Buat input text untuk mengedit nama file
                const inputGroup = document.createElement('div');
                inputGroup.className = 'mb-2';

                const fileNameLabel = document.createElement('label');
                fileNameLabel.textContent = `File ${index + 1}: `; // Label untuk file
                inputGroup.appendChild(fileNameLabel);

                const fileNameInput = document.createElement('input');
                fileNameInput.type = 'text';
                fileNameInput.name = `file_names[]`; // Array untuk menampung nama file
                fileNameInput.value = file.name; // Ambil nama file lengkap termasuk ekstensi
                fileNameInput.className = 'form-control';
                inputGroup.appendChild(fileNameInput);

                // Tampilkan nama asli file dan tambahkan ke DOM
                fileNamesDiv.appendChild(inputGroup);
            });
        }
    </script>
@endsection
