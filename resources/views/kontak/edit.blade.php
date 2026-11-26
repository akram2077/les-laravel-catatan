@extends('layouts.app')

@section('title', 'Edit Kontak')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        
        <!-- Notifikasi Sukses Berhasil Disimpan -->
        @if(session('sukses'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                {{ session('sukses') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">Edit Kontak</div>
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
                <form method="POST" action="/kontak/{{ $kontak->id }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" class="form-control" value="{{ old('nama', $kontak->nama) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Alamat Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $kontak->email) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="pesan" class="form-label">Pesan</label>
                        <textarea name="pesan" id="pesan" class="form-control" rows="5" required>{{ old('pesan', $kontak->pesan) }}</textarea>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-warning">Simpan Perubahan</button>
                    </div>
                    
                    <a href="/kontak/{{ $kontak->id }}" class="mt-2 d-block text-center">← Kembali ke Detail</a>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection