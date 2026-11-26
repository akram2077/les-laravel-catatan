@extends('layouts.app')

@section('title', 'Edit Catatan')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Edit Catatan</div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- PERHATIKAN: enctype="multipart/form-data" wajib ditambahkan di sini --}}
                <form method="POST" action="/catatan/{{ $catatan->id }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" name="judul" id="judul" class="form-control" value="{{ old('judul', $catatan->judul) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="isi" class="form-label">Isi Catatan</label>
                        <textarea name="isi" id="isi" class="form-control" rows="5" required>{{ old('isi', $catatan->isi) }}</textarea>
                    </div>

                    {{-- TAMBAHAN: Input file lampiran dan informasi lampiran saat ini --}}
                    <div class="mb-3">
                        <label for="lampiran" class="form-label">Lampiran (Opsional)</label>
                        
                        @if($catatan->lampiran)
                            <div class="mb-2">
                                <small class="text-muted d-block">Lampiran Saat Ini:</small>
                                @php
                                    $ext = pathinfo($catatan->lampiran, PATHINFO_EXTENSION);
                                @endphp
                                @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                                    <img src="{{ asset('storage/' . $catatan->lampiran) }}" alt="Lampiran" class="img-fluid rounded mt-1" style="max-height: 150px;">
                                @else
                                    <a href="{{ asset('storage/' . $catatan->lampiran) }}" target="_blank" class="btn btn-outline-secondary btn-sm mt-1">
                                        📎 Lihat Lampiran Lama
                                    </a>
                                @endif
                            </div>
                        @endif

                        <input type="file" name="lampiran" id="lampiran" class="form-control mt-2">
                        <div class="form-text">Biarkan kosong jika tidak ingin mengubah lampiran. Format: JPG, PNG, PDF, TXT (Maks. 2MB).</div>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    </div>
                    <a href="/catatan/{{ $catatan->id }}" class="mt-2 d-block text-center">← Kembali ke Detail</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection