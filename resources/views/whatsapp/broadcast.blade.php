@extends($layout)

@section('konten')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Broadcast WhatsApp</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('broadcast.send') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="message">Pesan:</label>
                    <textarea name="message" id="message" rows="5" class="form-control" required></textarea>
                </div>
                <button type="submit" class="btn btn-success mt-3">Kirim Broadcast</button>
            </form>
        </div>
    </div>
</div>
@endsection
