@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-header bg-primary text-white fw-bold py-3 rounded-top-4">
                Dashboard
            </div>
            <div class="card-body p-4">
                <div class="alert alert-success" role="alert">
                    You're logged in! Selamat datang, <strong>{{ Auth::user()->name }}</strong>.
                </div>
                <p class="text-muted">Anda berhasil masuk ke sistem aplikasi catatan menggunakan akun terdaftar.</p>
                
                <div class="mt-4">
                    <a href="/catatan" class="btn btn-primary">Kelola Catatan Saya</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection