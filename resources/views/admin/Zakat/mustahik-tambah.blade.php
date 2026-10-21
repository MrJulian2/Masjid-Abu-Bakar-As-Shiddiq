
@extends('admin.index')

@section('content')
    {{-- Content Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Data Mustahik Zakat Fitrah</h1>
                    @if (isset($periodeAktif))
                        <p class="text-muted mb-0">
                            Periode: {{ $periodeAktif->nama }}
                        </p>
                    @endif
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="#">Zakat Fitrah</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('zakat-mustahik.create') }}">Data Mustahik</a>
                        </li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="content">
        <div class="container-fluid">

            {{-- Pesan sukses --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-1"></i>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Pesan error --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Data belum dapat disimpan.</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-success">

                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-hand-holding-heart mr-1"></i>
                                Form Input Mustahik
                            </h3>
                        </div>

                        <form action="{{ route('zakat-mustahik.store') }}" method="POST">
                            @csrf

                            <div class="card-body">
                                <div class="row">

                                    {{-- Periode Zakat --}}
                                    <div class="form-group col-md-12">
                                        <label for="periode_id">
                                            Periode Zakat Fitrah
                                        </label>

                                        @if (isset($periodeAktif))
                                            <input type="hidden" name="periode_id"
                                                value="{{ $periodeAktif->id }}">

                                            <input type="text" class="form-control"
                                                value="{{ $periodeAktif->nama }}"
                                                readonly>

                                            <small class="text-muted">
                                                Data mustahik akan tercatat pada
                                                periode zakat yang aktif.
                                            </small>
                                        @else
                                            <div class="alert alert-warning mb-0">
                                                Belum ada periode zakat aktif.
                                                Silakan aktifkan periode terlebih dahulu.
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Nama --}}
                                    <div class="form-group col-md-6">
                                        <label for="nama">Nama Mustahik</label>
                                        <input type="text" name="nama" id="nama"
                                            class="form-control"
                                            value="{{ old('nama') }}"
                                            placeholder="Masukkan nama mustahik"
                                            maxlength="255" required>
                                    </div>

                                    {{-- Nomor HP --}}
                                    <div class="form-group col-md-6">
                                        <label for="nomor_hp">Nomor HP</label>
                                        <input type="text" name="nomor_hp"
                                            id="nomor_hp" class="form-control"
                                            value="{{ old('nomor_hp') }}"
                                            placeholder="Contoh: 081234567890"
                                            maxlength="20">
                                    </div>

                                    {{-- Alamat --}}
                                    <div class="form-group col-md-12">
                                        <label for="alamat">Alamat Lengkap</label>
                                        <textarea name="alamat" id="alamat" class="form-control" rows="3"
                                            placeholder="Masukkan alamat lengkap mustahik" required>{{ old('alamat') }}</textarea>
                                    </div>

                                    {{-- RT --}}
                                    <div class="form-group col-md-6">
                                        <label for="rt">RT</label>
                                        <input type="text" name="rt" id="rt"
                                            class="form-control"
                                            value="{{ old('rt') }}"
                                            placeholder="Masukkan nomor RT"
                                            maxlength="10">
                                    </div>

                                    {{-- RW --}}
                                    <div class="form-group col-md-6">
                                        <label for="rw">RW</label>
                                        <input type="text" name="rw" id="rw"
                                            class="form-control"
                                            value="{{ old('rw') }}"
                                            placeholder="Masukkan nomor RW"
                                            maxlength="10">
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-success"
                                    {{ !isset($periodeAktif) ? 'disabled' : '' }}>
                                    <i class="fas fa-save mr-1"></i>
                                    Simpan Mustahik
                                </button>

                                
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection
