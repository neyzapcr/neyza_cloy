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
            <a href="{{ route('pelanggan.index') }}" class="btn btn-primary">
                Kembali
            </a>
        </div>
    </div>

    <div class="row g-3">

        {{-- INFO PELANGGAN --}}
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

        {{-- FOTO + FILE PENDUKUNG --}}
        <div class="col-lg-7">
            <div class="card shadow-sm rounded-4 border-0 h-100">
                <div class="card-body">

                    {{-- FOTO PELANGGAN (lihat saja) --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Foto Pelanggan</h5>
                        <small class="text-muted">{{ count($fotos) }} foto</small>
                    </div>

                    @if(count($fotos))
                        <div class="d-flex flex-wrap gap-2 mb-4">
                            @foreach($fotos as $i => $foto)
                                <div class="border rounded-3 overflow-hidden shadow-sm"
                                     style="width:120px; height:120px;">
                                    <img src="{{ asset('storage/'.$foto) }}"
                                         class="w-100 h-100"
                                         style="object-fit:cover; cursor:pointer;"
                                         data-bs-toggle="modal"
                                         data-bs-target="#modalFoto{{ $i }}">
                                </div>

                                <div class="modal fade" id="modalFoto{{ $i }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-body p-2 text-center">
                                                <img src="{{ asset('storage/'.$foto) }}"
                                                     class="w-100 rounded-3"
                                                     style="object-fit:contain; max-height:80vh;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted mb-4">Belum ada foto pelanggan.</div>
                    @endif

                    <hr>

                    {{-- FILE PENDUKUNG (sesuai requirement) --}}
                    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
                        <h5 class="mb-0">File Pendukung</h5>
                        <small class="text-muted">{{ $files->count() }} file</small>
                    </div>

                    {{-- form upload file pendukung --}}
                    <form action="{{ route('pelanggan.file.upload', $dataPelanggan->pelanggan_id) }}"
                          method="POST" enctype="multipart/form-data" class="mb-3">
                        @csrf
                        <input type="hidden" name="ref_table" value="pelanggan">
                        <input type="hidden" name="ref_id" value="{{ $dataPelanggan->pelanggan_id }}">

                        <div class="input-group w-100">
                            <input type="file" name="files[]" class="form-control" multiple required>
                            <button class="btn btn-info" type="submit">Upload</button>
                        </div>
                        <small class="text-muted">Bisa upload satu atau beberapa file.</small>
                    </form>

                    {{-- list file --}}
                    @if($files->count())
                        <div class="list-group">
                            @foreach($files as $j => $file)
                                @php
                                    $url = asset('storage/'.$file->filename);
                                    $ext = strtolower(pathinfo($file->filename, PATHINFO_EXTENSION));
                                    $isImage = in_array($ext, ['jpg','jpeg','png','gif','webp']);
                                    $isPdf = $ext === 'pdf';
                                @endphp

                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($isImage)
                                            <img src="{{ $url }}" style="width:50px;height:50px;object-fit:cover;border-radius:8px;">
                                        @endif

                                        {{-- klik nama file -> preview modal --}}
                                        <button type="button"
                                                class="btn btn-link p-0 text-decoration-none"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modalFile{{ $j }}">
                                            {{ basename($file->filename) }}
                                        </button>
                                    </div>

                                    {{-- tombol hapus file pendukung --}}
                                    <form action="{{ route('pelanggan.file.destroy', [$dataPelanggan->pelanggan_id, $file->id]) }}"
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                                onclick="return confirm('Hapus file ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>

                                {{-- modal preview --}}
                                <div class="modal fade" id="modalFile{{ $j }}" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-xl">
                                        <div class="modal-content rounded-4 border-0 shadow">
                                            <div class="modal-body p-2">
                                                @if($isImage)
                                                    <img src="{{ $url }}"
                                                         class="w-100 rounded-3"
                                                         style="object-fit:contain; max-height:80vh;">
                                                @elseif($isPdf)
                                                    <iframe src="{{ $url }}"
                                                            style="width:100%; height:80vh; border:0; border-radius:8px;">
                                                    </iframe>
                                                @else
                                                    <div class="p-3 text-center text-muted">
                                                        File ini tidak bisa dipreview langsung.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">Belum ada file pendukung.</div>
                    @endif

                </div>
            </div>
        </div>

    </div>
</div>
@endsection
