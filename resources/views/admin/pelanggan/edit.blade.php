@extends('layouts.admin.app')

@section('content')
<div class="py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="d-none d-md-inline-block mb-3">
        <ol class="breadcrumb breadcrumb-dark breadcrumb-transparent">
            <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Edit Data</li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
        <div>
            <h1 class="h4 mb-1 text-dark">Edit Pelanggan</h1>
            <p class="text-muted mb-0">Form untuk memperbarui data pelanggan.</p>
        </div>
        <div>
            <a href="{{ route('pelanggan.index') }}" class="btn btn-primary">Kembali</a>
        </div>
    </div>

    <div class="row">
        <div class="col-12 mb-4">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-body">

                    <form action="{{ route('pelanggan.update', $dataPelanggan->pelanggan_id) }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-4">

                            {{-- ================= LEFT SIDE (INPUT DATA) ================= --}}
                            <div class="col-lg-8">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" name="first_name"
                                               value="{{ old('first_name', $dataPelanggan->first_name) }}"
                                               id="first_name" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="birthday" class="form-label">Birthday</label>
                                        <input type="date" name="birthday"
                                               value="{{ old('birthday', $dataPelanggan->birthday) }}"
                                               id="birthday" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" name="last_name"
                                               value="{{ old('last_name', $dataPelanggan->last_name) }}"
                                               id="last_name" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="gender" class="form-label">Gender</label>
                                        <select class="form-select" id="gender" name="gender">
                                            <option value="" disabled>-- Pilih Gender --</option>
                                            <option value="Female" {{ old('gender', $dataPelanggan->gender) == 'Female' ? 'selected' : '' }}>
                                                Female
                                            </option>
                                            <option value="Male" {{ old('gender', $dataPelanggan->gender) == 'Male' ? 'selected' : '' }}>
                                                Male
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email"
                                               value="{{ old('email', $dataPelanggan->email) }}"
                                               id="email" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" name="phone"
                                               value="{{ old('phone', $dataPelanggan->phone) }}"
                                               id="phone" class="form-control">
                                    </div>

                                </div>

                                {{-- Buttons --}}
                                <div class="d-flex gap-2 mt-4">
                                    <button type="submit" class="btn btn-info flex-grow-1">
                                        Simpan Perubahan
                                    </button>
                                    <a href="{{ route('pelanggan.index') }}"
                                       class="btn btn-outline-secondary flex-grow-1">
                                        Batal
                                    </a>
                                </div>
                            </div>

                            {{-- ================= RIGHT SIDE (FOTO PANEL) ================= --}}
                            <div class="col-lg-4">
                                <div class="border rounded-4 p-3 h-100">

                                    <h6 class="mb-3">Foto Pelanggan</h6>

                                    <div class="mb-3">
                                        <input type="file" name="foto[]"
                                               id="foto" class="form-control"
                                               multiple accept="image/*">
                                        <small class="text-muted">Bisa pilih lebih dari 1 foto.</small>
                                    </div>

                                    @if(count($fotos))
                                        <div class="mt-3">
                                            <label class="form-label">Foto Saat Ini</label>

                                            {{-- bikin nyamping + scroll --}}
                                            <div class="d-flex flex-nowrap gap-2 overflow-auto pb-2">
                                                @foreach($fotos as $foto)
                                                    <div class="position-relative border rounded-3 overflow-hidden shadow-sm flex-shrink-0"
                                                         style="width:110px; height:110px;">
                                                        <img src="{{ asset('storage/'.$foto) }}"
                                                             style="width:100%; height:100%; object-fit:cover;">

                                                        {{-- tombol hapus tetap di edit --}}
                                                        <form action="{{ route('pelanggan.foto.destroy', $dataPelanggan->pelanggan_id) }}"
                                                              method="POST"
                                                              class="position-absolute top-0 end-0 m-1">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="foto" value="{{ $foto }}">
                                                            <button type="submit"
                                                                    class="btn btn-danger btn-sm rounded-circle"
                                                                    style="width:26px; height:26px; padding:0;"
                                                                    onclick="return confirm('Hapus foto ini?')">
                                                                &times;
                                                            </button>
                                                        </form>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-muted mt-2">Belum ada foto.</div>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
