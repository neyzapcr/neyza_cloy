@extends('layouts.admin.app')

@section('content')
    <div class="py-4">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block mb-3">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="{{ route('pelanggan.index') }}">Pelanggan</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Detail Pelanggan</li>
            </ol>
        </nav>

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <div>
                <h1 class="h4 mb-1 text-dark">Detail Pelanggan</h1>
                <p class="text-muted mb-0">Informasi lengkap data pelanggan.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('pelanggan.edit', $dataPelanggan->pelanggan_id) }}" class="btn btn-warning">
                    Edit
                </a>
                <a href="{{ route('pelanggan.index') }}" class="btn btn-primary">
                    Kembali
                </a>
            </div>
        </div>

        <div class="row g-3">
            {{-- Info Pelanggan --}}
            <div class="col-lg-5">
                <div class="card shadow-sm rounded-4 border-0 h-100">
                    <div class="card-body">
                        <h5 class="mb-3">Data Pelanggan</h5>

                        <div class="mb-2 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">First Name</span>
                            <span class="fw-semibold">{{ $dataPelanggan->first_name ?? '-' }}</span>
                        </div>

                        <div class="mb-2 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Last Name</span>
                            <span class="fw-semibold">{{ $dataPelanggan->last_name ?? '-' }}</span>
                        </div>

                        <div class="mb-2 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Birthday</span>
                            <span class="fw-semibold">
                                {{ $dataPelanggan->birthday ? \Carbon\Carbon::parse($dataPelanggan->birthday)->format('d M Y') : '-' }}
                            </span>
                        </div>

                        <div class="mb-2 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Gender</span>
                            <span class="fw-semibold">{{ $dataPelanggan->gender ?? '-' }}</span>
                        </div>

                        <div class="mb-2 d-flex justify-content-between border-bottom pb-2">
                            <span class="text-muted">Email</span>
                            <span class="fw-semibold">{{ $dataPelanggan->email ?? '-' }}</span>
                        </div>

                        <div class="mb-0 d-flex justify-content-between">
                            <span class="text-muted">Phone</span>
                            <span class="fw-semibold">{{ $dataPelanggan->phone ?? '-' }}</span>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Foto Pelanggan --}}
            <div class="col-lg-7">
                <div class="card shadow-sm rounded-4 border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Foto Pelanggan</h5>
                            <small class="text-muted">
                                {{ count($fotos) }} file
                            </small>

                        </div>

                        @if (count($fotos))
                            @foreach ($fotos as $foto)
                                <img src="{{ asset('storage/' . $foto) }}" width="120" class="me-2 mb-2 rounded">
                            @endforeach
                        @else
                            <div class="text-muted">Belum ada foto.</div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
