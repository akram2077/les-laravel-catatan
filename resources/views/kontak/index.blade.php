@extends('layouts.app')

@section('title', 'Daftar Kontak')

@section('content')
<div class="row justify-content-between mb-3">
    <div class="col">
        <h2>Daftar Kontak</h2>
    </div>
    <div class="col-auto">
        <a href="/kontak/tambah" class="btn btn-success">+ Tambah Kontak Baru</a>
    </div>
</div>

@if(session('sukses'))
    <div class="alert alert-success">{{ session('sukses') }}</div>
@endif

@if(isset($kontak) && $kontak->count() > 0)
    <div class="row">
        @foreach($kontak as $item)
            <div class="col-md-6 mb-3">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span><strong>{{ $item->nama }}</strong></span>
                        <small class="text-muted">{{ $item->created_at->format('d M Y H:i') }}</small>
                    </div>
                    <div class="card-body">
                        <p class="card-text mb-1"><strong>Email:</strong> {{ $item->email }}</p>
                        <p class="card-text"><strong>Pesan:</strong> {{ Str::limit($item->pesan, 100) }}</p>
                        
                        <a href="/kontak/{{ $item->id }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                        <a href="/kontak/{{ $item->id }}/edit" class="btn btn-sm btn-outline-warning">Edit</a>
                        
                       <form action="/kontak/{{ $item->id }}" method="POST" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="button" class="btn btn-sm btn-outline-danger" 
        onclick="bukaModalHapus('/catatan/{{ $item->id }}', '{{ addslashes($item->judul) }}')">
    <i class="fas fa-trash"></i>
</button>
</form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="alert alert-info">
        Belum ada data kontak. <a href="/kontak/tambah">Buat kontak pertama!</a>
    </div>
@endif
@endsection