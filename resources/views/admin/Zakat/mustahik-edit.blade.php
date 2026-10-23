
@extends('admin.index')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                <div>
                    <h1 class="mb-1">
                        <i class="fas fa-user-edit text-success mr-2"></i>
                        Edit Data Mustahik
                    </h1>
                    <small class="text-muted">
                        Perbarui data penerima zakat fitrah
                    </small>
                </div>

                <a href="{{ route('zakat-mustahik.kelola') }}"
                    class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left mr-1"></i>
                    Kembali
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            {{-- NOTIFIKASI --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-1"></i>
                    {{ session('success') }}

                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>
                        <i class="fas fa-exclamation-circle mr-1"></i>
                        Data belum dapat disimpan.
                    </strong>

                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- INFORMASI PERIODE --}}
            <div class="alert alert-light border">
                <i class="fas fa-calendar-alt text-success mr-1"></i>
                <strong>Periode data:</strong>
                {{ $mustahik->periode->nama ?? $periodeAktif->nama ?? 'Periode tidak tersedia' }}

                <div class="small text-muted mt-1">
                    Mengedit data tidak mengubah periode zakat Mustahik.
                </div>
            </div>

            {{-- FORM EDIT --}}
            <div class="card card-outline card-success shadow-sm">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-address-card mr-2"></i>
                        Formulir Mustahik
                    </h3>
                </div>

                <form action="{{ route('zakat-mustahik.update', $mustahik->id) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">
                        <div class="row">

                            {{-- NAMA --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nama">
                                        Nama Mustahik
                                        <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                        name="nama"
                                        id="nama"
                                        class="form-control @error('nama') is-invalid @enderror"
                                        value="{{ old('nama', $mustahik->nama) }}"
                                        placeholder="Masukkan nama lengkap mustahik"
                                        maxlength="255"
                                        required>

                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- NOMOR HP --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nomor_hp">Nomor HP</label>

                                    <input type="text"
                                        name="nomor_hp"
                                        id="nomor_hp"
                                        class="form-control @error('nomor_hp') is-invalid @enderror"
                                        value="{{ old('nomor_hp', $mustahik->nomor_hp) }}"
                                        placeholder="Contoh: 081234567890"
                                        maxlength="20">

                                    @error('nomor_hp')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- ALAMAT --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="alamat">Alamat</label>

                                    <textarea name="alamat"
                                        id="alamat"
                                        rows="3"
                                        class="form-control @error('alamat') is-invalid @enderror"
                                        placeholder="Masukkan alamat lengkap">{{ old('alamat', $mustahik->alamat) }}</textarea>

                                    @error('alamat')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- RT --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rt">RT</label>

                                    <input type="text"
                                        name="rt"
                                        id="rt"
                                        class="form-control @error('rt') is-invalid @enderror"
                                        value="{{ old('rt', $mustahik->rt) }}"
                                        placeholder="Contoh: 001"
                                        maxlength="10">

                                    @error('rt')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- RW --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rw">RW</label>

                                    <input type="text"
                                        name="rw"
                                        id="rw"
                                        class="form-control @error('rw') is-invalid @enderror"
                                        value="{{ old('rw', $mustahik->rw) }}"
                                        placeholder="Contoh: 002"
                                        maxlength="10">

                                    @error('rw')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle mr-1"></i>
                            Periode zakat dan status pengambilan kupon tidak diubah melalui formulir ini.
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-between flex-wrap">
                        <a href="{{ route('zakat-mustahik.kelola') }}"
                            class="btn btn-secondary mb-1">
                            <i class="fas fa-times mr-1"></i>
                            Batal
                        </a>

                        <button type="submit" class="btn btn-success mb-1">
                            <i class="fas fa-save mr-1"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </section>
@endsection
