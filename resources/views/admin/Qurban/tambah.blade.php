
@extends('admin.index')

@section('head')
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">

    <!-- Daterange picker -->
    <link rel="stylesheet"
        href="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
    {{-- Content Header --}}
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Data Penerima Qurban</h1>

                    @if ($periodeAktif)
                        <p class="text-muted mb-0">
                            Periode: {{ $periodeAktif->nama }}
                        </p>
                    @endif
                </div>

                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="#">Qurban</a>
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
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ session('error') }}

                    <button type="button" class="close" data-dismiss="alert"
                        aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            {{-- Validasi periode aktif --}}
            @if (!$periodeAktif)
                <div class="alert alert-warning">
                    <h5>
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Periode Qurban Belum Aktif
                    </h5>

                    Belum ada periode Qurban yang aktif. Silakan aktifkan
                    periode Qurban terlebih dahulu sebelum menambahkan
                    penerima dan membuat kupon.

                    <hr>

                    <a href="{{ url('/admin/qurban-periode') }}"
                        class="btn btn-danger btn-sm">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Kelola Periode Qurban
                    </a>
                </div>
            @endif

            {{-- Error validasi form --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Data belum dapat disimpan:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">

                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-hand-holding-heart mr-1"></i>
                                Input Penerima Qurban
                            </h3>
                        </div>

                        <form action="{{ url('/admin/qurban/add') }}"
                            method="POST">
                            @csrf

                            <div class="card-body">

                                {{-- Informasi periode --}}
                                @if ($periodeAktif)
                                    <div class="form-group">
                                        <label>Periode Qurban</label>
                                        <input type="text"
                                            class="form-control"
                                            value="{{ $periodeAktif->nama }}"
                                            readonly>

                                        <small class="text-muted">
                                            Data penerima dan kupon akan
                                            dicatat pada periode ini.
                                        </small>
                                    </div>
                                @endif

                                <div class="row">

                                    {{-- Nama --}}
                                    <div class="form-group col-md-6">
                                        <label>Nama</label>
                                        <input type="text"
                                            name="nama"
                                            class="form-control"
                                            value="{{ old('nama') }}"
                                            placeholder="Masukkan nama penerima"
                                            maxlength="255"
                                            required>
                                    </div>

                                    {{-- Nomor HP --}}
                                    <div class="form-group col-md-6">
                                        <label>No HP</label>
                                        <input type="text"
                                            name="no_hp"
                                            class="form-control"
                                            value="{{ old('no_hp') }}"
                                            placeholder="Contoh: 081234567890">
                                    </div>

                                    {{-- Alamat --}}
                                    <div class="form-group col-md-12">
                                        <label>Alamat</label>
                                        <textarea name="alamat"
                                            class="form-control"
                                            rows="3"
                                            placeholder="Masukkan alamat">{{ old('alamat', '-') }}</textarea>
                                    </div>

                                    {{-- RT --}}
                                    <div class="form-group col-md-3">
                                        <label>RT</label>
                                        <input type="text"
                                            name="rt"
                                            class="form-control"
                                            value="{{ old('rt') }}"
                                            required>
                                    </div>

                                    {{-- RW --}}
                                    <div class="form-group col-md-3">
                                        <label>RW</label>
                                        <input type="text"
                                            name="rw"
                                            class="form-control"
                                            value="{{ old('rw') }}"
                                            required>
                                    </div>

                                    {{-- Jumlah kupon --}}
                                    <div class="form-group col-md-3">
                                        <label>Jumlah Kupon</label>
                                        <input type="number"
                                            name="jumlah_kupon"
                                            class="form-control"
                                            value="{{ old('jumlah_kupon', 1) }}"
                                            min="1"
                                            required>
                                    </div>

                                </div>
                            </div>

                            <div class="card-footer">
                                <button type="submit"
                                    class="btn btn-primary"
                                    {{ !$periodeAktif ? 'disabled' : '' }}>
                                    <i class="fas fa-save mr-1"></i>
                                    Simpan &amp; Generate Kupon
                                </button>

                                
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('script')
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <script>
        $('#reservationdate1, #reservationdate2').datetimepicker({
            format: 'DD-MM-YYYY',
        });
    </script>
@endsection
