@extends('layouts.admin.app')

@section('content')
    <div class="py-4">

        {{-- Breadcrumb --}}
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
            <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
                <li class="breadcrumb-item">
                    <a href="#">
                        <svg class="icon icon-xxs" fill="none" stroke="currentColor"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                    </a>
                </li>
                <li class="breadcrumb-item"><a href="#">User</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit User</li>
            </ol>
        </nav>

        {{-- Header --}}
        <div class="d-flex justify-content-between w-100 flex-wrap mb-3">
            <div>
                <h1 class="h4 fw-bold mb-1">Edit User</h1>
                <p class="text-muted mb-0">Perbarui informasi user dengan mudah.</p>
            </div>
            <div>
                <a href="{{ route('user.index') }}" class="btn btn-primary px-4 rounded-3">
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- Card Form --}}
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">

                    <form action="{{ route('user.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-lg-5 col-sm-8">

                                {{-- Nama --}}
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" name="name" id="name"
                                        value="{{ $user->name }}"
                                        class="form-control shadow-sm rounded-3" required>
                                </div>

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email</label>
                                    <input type="email" name="email" id="email"
                                        value="{{ $user->email }}"
                                        class="form-control shadow-sm rounded-3" required>
                                </div>

                                {{-- Password --}}
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">
                                        Password (opsional)
                                    </label>
                                    <input type="password" name="password" id="password"
                                        class="form-control shadow-sm rounded-3">
                                    <small class="text-muted">Biarkan kosong jika tidak ingin mengganti password.</small>
                                </div>

                                {{-- Foto Profil --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Foto Profil</label>

                                    <div class="d-flex align-items-center gap-3 mb-2">
                                        @if($user->profile_picture)
                                            <img src="{{ Storage::url($user->profile_picture) }}"
                                                 class="rounded-circle shadow-sm border"
                                                 width="80" height="80" style="object-fit: cover;">
                                             @endif
                                        <div class="flex-grow-1">
                                            <input type="file" name="profile_picture"
                                                class="form-control shadow-sm rounded-3">
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary px-4 rounded-3 shadow-sm">
                                Simpan Perubahan
                            </button>

                            <a href="{{ route('user.index') }}" class="btn btn-outline-secondary px-4 rounded-3">
                                Batal
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
