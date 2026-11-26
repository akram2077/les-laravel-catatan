@extends('layouts.app')

@section('title', 'Profil Pengguna')

@section('content')
<div class="row justify-content-center pb-5">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Profil Pengguna</h2>
                <p class="text-muted mb-0 small">Kelola informasi akun, kata sandi, dan keamanan profil Anda.</p>
            </div>
            <a href="/catatan" class="btn btn-outline-secondary btn-sm">← Kembali ke Catatan</a>
        </div>

        {{-- 1. Informasi Profil --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4">
                <h5 class="fw-bold mb-0 text-primary">Informasi Profil</h5>
                <small class="text-muted">Perbarui nama lengkap dan alamat email akun Anda.</small>
            </div>
            <div class="card-body p-4">
                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Profil berhasil diperbarui!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="post" action="{{ route('profile.update') }}">
                    @csrf
                    @method('patch')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-semibold">Nama</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            required
                            autofocus
                            autocomplete="name"
                        >
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}"
                            required
                            autocomplete="username"
                        >
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div class="alert alert-warning mt-3 mb-0 small">
                                Alamat email Anda belum terverifikasi.
                                <button form="send-verification" type="submit" class="btn btn-link btn-sm p-0 align-baseline">
                                    Klik di sini untuk mengirim ulang email verifikasi.
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                    </div>
                </form>

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="d-none">
                        @csrf
                    </form>
                @endif
            </div>
        </div>

        {{-- 2. Ubah Password --}}
        <div class="card shadow-sm border-0 rounded-4 mb-4">
            <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4">
                <h5 class="fw-bold mb-0 text-primary">Ubah Password</h5>
                <small class="text-muted">Gunakan password yang panjang dan acak agar akun tetap aman.</small>
            </div>
            <div class="card-body p-4">
                @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Password berhasil diperbarui!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="post" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="mb-3">
                        <label for="update_password_current_password" class="form-label fw-semibold">Password Saat Ini</label>
                        <input
                            type="password"
                            id="update_password_current_password"
                            name="current_password"
                            class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                            autocomplete="current-password"
                        >
                        @error('current_password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="update_password_password" class="form-label fw-semibold">Password Baru</label>
                        <input
                            type="password"
                            id="update_password_password"
                            name="password"
                            class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                            autocomplete="new-password"
                        >
                        @error('password', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="update_password_password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                        <input
                            type="password"
                            id="update_password_password_confirmation"
                            name="password_confirmation"
                            class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                            autocomplete="new-password"
                        >
                        @error('password_confirmation', 'updatePassword')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary px-4">Update Password</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- 3. Hapus Akun --}}
        <div class="card shadow-sm border-0 rounded-4 border-start border-danger border-4">
            <div class="card-header bg-white border-bottom py-3 px-4 rounded-top-4">
                <h5 class="fw-bold mb-0 text-danger">Hapus Akun</h5>
                <small class="text-muted">Setelah akun dihapus, seluruh data terkait akan hilang secara permanen.</small>
            </div>
            <div class="card-body p-4">
                <p class="text-muted small mb-3">
                    Sebelum menghapus akun, pastikan Anda sudah menyimpan data atau catatan penting yang ingin dipertahankan.
                </p>

                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
                    Hapus Akun
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Konfirmasi Hapus Akun --}}
<div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold text-danger" id="confirmUserDeletionModalLabel">Konfirmasi Hapus Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Tindakan ini tidak dapat dibatalkan. Masukkan password Anda untuk mengonfirmasi penghapusan akun secara permanen.
                    </p>

                    <div class="mb-2">
                        <label for="delete_user_password" class="form-label fw-semibold">Password</label>
                        <input
                            type="password"
                            id="delete_user_password"
                            name="password"
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                            placeholder="Masukkan password Anda"
                            required
                        >
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Akun</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Buka otomatis modal jika ada error validasi saat hapus akun --}}
@if ($errors->userDeletion->isNotEmpty())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('confirmUserDeletionModal');
            if (modalEl && typeof bootstrap !== 'undefined') {
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            }
        });
    </script>
@endif
@endsection