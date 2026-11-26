@extends('layouts.app')

@section('title', $catatan->judul)

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        @if(session('sukses'))
            <div class="alert alert-success">{{ session('sukses') }}</div>
        @endif

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="mb-1">{{ $catatan->judul }}</h4>
                    <small class="text-muted">
                        Dibuat pada: {{ $catatan->created_at->copy()->locale('id')->isoFormat('dddd, D MMMM Y [pukul] HH:mm') }}
                    </small>
                </div>
                @if($catatan->lampiran)
                    <span class="badge bg-info">Ada lampiran</span>
                @endif
            </div>

            <div class="card-body">
                <p class="card-text">{!! nl2br(e($catatan->isi)) !!}</p>

                {{-- Bagian Lampiran --}}
                @if($catatan->lampiran)
                    <hr>
                    <p class="mb-0"><strong>Lampiran:</strong></p>
                    @php
                        $ext = pathinfo($catatan->lampiran, PATHINFO_EXTENSION);
                    @endphp
                    @if(in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                        <img src="{{ asset('storage/' . $catatan->lampiran) }}" alt="Lampiran" class="img-fluid mt-2" style="max-height:300px;">
                    @else
                        <a href="{{ asset('storage/' . $catatan->lampiran) }}" target="_blank" class="btn btn-outline-secondary btn-sm mt-2">
                            📎 Lihat Lampiran
                        </a>
                    @endif
                @endif
            </div>

            <div class="card-footer text-end">
                <a href="/catatan" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>

                @if($catatan->is_archived)
                    <form action="/catatan/{{ $catatan->id }}/unarchive" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-undo"></i> Pulihkan
                        </button>
                    </form>
                @else
                    <form action="/catatan/{{ $catatan->id }}/archive" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary" onclick="return confirm('Arsipkan catatan ini?')">
                            <i class="fas fa-archive"></i> Arsipkan
                        </button>
                    </form>
                @endif

                <a href="/catatan/{{ $catatan->id }}/edit" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit
                </a>

                <button type="button"
                        class="btn btn-danger"
                        onclick="bukaModalHapus('/catatan/{{ $catatan->id }}', '{{ addslashes($catatan->judul) }}')">
                    <i class="fas fa-trash"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>
@endsection