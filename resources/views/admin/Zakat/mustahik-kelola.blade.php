
@extends('admin.index')

@section('head')
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <style>
        :root {
            --masjid-green: #14532d;
            --masjid-light: #e8f5ee;
        }

        .content-wrapper {
            background: #f4f7fb;
        }

        .mustahik-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 18px;
        }

        .mustahik-heading h1 {
            font-size: 25px;
            font-weight: 700;
            color: #243746;
            margin: 0;
        }

        .active-period {
            padding: 12px 15px;
            margin-bottom: 18px;
            color: var(--masjid-green);
            background: var(--masjid-light);
            border: 1px solid #c5e5d2;
            border-radius: 6px;
        }

        .stat-card {
            position: relative;
            overflow: hidden;
            border-radius: 7px;
            color: #fff;
            min-height: 115px;
            margin-bottom: 16px;
            box-shadow: 0 2px 7px #0000000c;
        }

        .stat-card .inner {
            padding: 17px;
            position: relative;
            z-index: 1;
        }

        .stat-card h3 {
            font-size: 29px;
            font-weight: 700;
            margin: 5px 0;
        }

        .stat-card p {
            margin-bottom: 0;
        }

        .stat-card .icon {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 46px;
            opacity: .22;
        }

        .stat-total { background: linear-gradient(120deg, #14532d, #087f5b); }
        .stat-done { background: linear-gradient(120deg, #198754, #20a36a); }
        .stat-pending { background: linear-gradient(120deg, #dc3545, #ed4c4c); }
        .stat-area { background: linear-gradient(120deg, #6548c8, #8062df); }

        .masjid-panel {
            border: 1px solid #dce7f0;
            border-radius: 7px;
            margin-bottom: 18px;
            box-shadow: 0 2px 6px #1e32460a;
        }

        .masjid-panel > .card-header {
            background: var(--masjid-green);
            color: white;
            padding: 13px 16px;
        }

        .masjid-panel > .card-header .card-title {
            font-weight: 600;
        }

        .rw-card {
            border: 1px solid #d7e6ef;
            border-radius: 6px;
            overflow: hidden;
            height: 100%;
            background: #fff;
        }

        .rw-heading {
            padding: 10px 12px;
            color: #fff;
            background: #16845b;
        }

        .rw-heading strong,
        .rw-heading small {
            display: block;
        }

        .rt-card {
            padding: 10px;
            height: 100%;
            background: #eef9f5;
            border: 1px solid #c9e8dd;
            border-radius: 5px;
        }

        .rt-card .rt-title {
            font-size: 12px;
            font-weight: 700;
            color: var(--masjid-green);
        }

        .rt-card .rt-total {
            font-size: 23px;
            font-weight: 700;
        }

        .rt-summary {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            font-size: 11px;
        }

        .filter-panel {
            padding: 16px;
            margin-bottom: 18px;
            background: #fff;
            border: 1px solid #dce7f0;
            border-radius: 7px;
        }

        .filter-title {
            font-weight: 700;
            color: var(--masjid-green);
            margin-bottom: 12px;
        }

        .filter-panel label {
            font-size: 12px;
            font-weight: 600;
            color: #526579;
        }

        .filter-panel .form-control {
            height: 38px;
            font-size: 13px;
            border-color: #d5e0ea;
        }

        .btn-masjid {
            background: var(--masjid-green);
            color: white;
        }

        .btn-masjid:hover {
            color: white;
            background: #0f4223;
        }

        .table-mustahik {
            font-size: 12px;
            margin-bottom: 0;
        }

        .table-mustahik thead th {
            background: #edf3f8;
            color: #30465a;
            white-space: nowrap;
            vertical-align: middle;
        }

        .table-mustahik td {
            vertical-align: middle;
        }

        .badge-status {
            display: inline-block;
            padding: 5px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 600;
            white-space: nowrap;
        }

        .badge-sudah {
            background: #198754;
            color: white;
        }

        .badge-belum {
            background: #dc3545;
            color: white;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 5px;
        }

        .action-buttons .btn {
            font-size: 11px;
        }

        .table-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .toolbar-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        @media (max-width: 767px) {
            .mustahik-heading h1 {
                font-size: 21px;
            }
        }

        @media print {
            .main-sidebar,
            .main-header,
            .content-header,
            .filter-panel,
            .toolbar-actions,
            .action-buttons,
            .main-footer {
                display: none !important;
            }

            .content-wrapper {
                margin-left: 0 !important;
                background: white !important;
            }

            .card {
                box-shadow: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="mustahik-heading">
                <div>
                    <h1>
                        <i class="fas fa-users mr-2 text-success"></i>
                        Data Mustahik Zakat Fitrah
                    </h1>
                    <small class="text-muted">
                        Pengelolaan data penerima zakat fitrah
                    </small>
                </div>

                <div class="text-muted">
                    <i class="fas fa-home mr-1"></i> Home
                    <i class="fas fa-chevron-right mx-2"></i> Mustahik
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle mr-1"></i>
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert">
                        <span>&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- PERIODE --}}
            @if ($periodeAktif)
                <div class="active-period">
                    <i class="fas fa-calendar-check mr-2"></i>
                    <strong>Periode Aktif:</strong>
                    {{ $periodeAktif->nama }}
                    <span class="badge badge-success ml-2">Aktif</span>
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    <strong>Belum ada periode zakat aktif.</strong>
                    Aktifkan periode zakat terlebih dahulu untuk menambahkan data.
                </div>
            @endif

            {{-- STATISTIK --}}
            <div class="row">
                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card stat-total">
                        <div class="inner">
                            <p>Total Mustahik</p>
                            <h3>{{ $totalMustahik }}</h3>
                            <small>Orang terdaftar</small>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card stat-done">
                        <div class="inner">
                            <p>Kupon Sudah Diambil</p>
                            <h3>{{ $sudahDisalurkan }}</h3>
                            <small>Memiliki kupon yang diambil</small>
                        </div>
                        <div class="icon"><i class="fas fa-check-circle"></i></div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card stat-pending">
                        <div class="inner">
                            <p>Belum Diambil</p>
                            <h3>{{ $belumDisalurkan }}</h3>
                            <small>Belum memiliki kupon yang diambil</small>
                        </div>
                        <div class="icon"><i class="fas fa-clock"></i></div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="stat-card stat-area">
                        <div class="inner">
                            <p>Wilayah RT/RW</p>
                            <h3>{{ $totalWilayah }}</h3>
                            <small>Kombinasi RT/RW terdata</small>
                        </div>
                        <div class="icon"><i class="fas fa-map-marker-alt"></i></div>
                    </div>
                </div>
            </div>

            {{-- DISTRIBUSI RW / RT --}}
            <div class="card masjid-panel">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-map-marked-alt mr-2"></i>
                        Distribusi Mustahik per RW dan RT
                    </h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        @forelse ($rwGrouped as $rw => $rwData)
                            <div class="col-xl-3 col-md-6 mb-3">
                                <div class="rw-card">
                                    <div class="rw-heading">
                                        <strong>
                                            <i class="fas fa-map-marker-alt mr-1"></i>
                                            RW {{ str_pad($rw, 3, '0', STR_PAD_LEFT) }}
                                        </strong>
                                        <small>Total: {{ $rwData['total'] }} mustahik</small>
                                    </div>

                                    <div class="p-2">
                                        <div class="row">
                                            @forelse ($rwData['rts'] as $rt => $rtData)
                                                <div class="col-6 mb-2">
                                                    <div class="rt-card">
                                                        <div class="rt-title">
                                                            RT {{ str_pad($rt, 3, '0', STR_PAD_LEFT) }}
                                                        </div>
                                                        <div class="rt-total">{{ $rtData['total'] }}</div>
                                                        <div class="rt-summary">
                                                            <span class="text-success">
                                                                <i class="fas fa-circle"></i>
                                                                {{ $rtData['sudah'] }}
                                                            </span>
                                                            <span class="text-danger">
                                                                <i class="fas fa-circle"></i>
                                                                {{ $rtData['belum'] }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div class="col-12">
                                                    <small class="text-muted">Belum ada data RT.</small>
                                                </div>
                                            @endforelse
                                        </div>
                                        <small class="text-muted">
                                            <span class="text-success">● Sudah diambil</span>
                                            <span class="text-danger ml-2">● Belum diambil</span>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center text-muted py-4">
                                Belum ada data distribusi wilayah.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="filter-panel">
                <div class="filter-title">
                    <i class="fas fa-filter mr-1"></i> Filter Pencarian
                </div>

                <form action="{{ url()->current() }}" method="GET">
                    <div class="row align-items-end">
                        <div class="col-lg-4 col-md-6 mb-2">
                            <label for="nama">Nama Mustahik</label>
                            <input type="text"
                                name="nama"
                                id="nama"
                                class="form-control"
                                placeholder="Cari nama mustahik..."
                                value="{{ request('nama') }}">
                        </div>

                        <div class="col-lg-2 col-md-3 col-6 mb-2">
                            <label for="rt">RT</label>
                            <select name="rt" id="rt" class="form-control">
                                <option value="">Semua RT</option>
                                @foreach ($daftarRT as $rt)
                                    <option value="{{ $rt }}" @selected((string) request('rt') === (string) $rt)>
                                        {{ $rt }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-3 col-6 mb-2">
                            <label for="rw">RW</label>
                            <select name="rw" id="rw" class="form-control">
                                <option value="">Semua RW</option>
                                @foreach ($daftarRW as $rw)
                                    <option value="{{ $rw }}" @selected((string) request('rw') === (string) $rw)>
                                        {{ $rw }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6 mb-2">
                            <label for="status">Status Kupon</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">Semua Status</option>
                                <option value="sudah" @selected(request('status') === 'sudah')>
                                    Sudah Diambil
                                </option>
                                <option value="belum" @selected(request('status') === 'belum')>
                                    Belum Diambil
                                </option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-6 mb-2">
                            <button type="submit" class="btn btn-masjid">
                                <i class="fas fa-search mr-1"></i> Cari
                            </button>
                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                                <i class="fas fa-sync-alt"></i>
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            {{-- TABEL --}}
            <div class="card masjid-panel">
                <div class="card-header">
                    <div class="table-toolbar">
                        <h3 class="card-title">
                            <i class="fas fa-list mr-2"></i>
                            Data Mustahik
                        </h3>

                        <div class="toolbar-actions">
                            @if ($periodeAktif)
                                <a href="{{ route('zakat-mustahik.create') }}"
                                    class="btn btn-sm btn-success">
                                    <i class="fas fa-plus mr-1"></i> Tambah Mustahik
                                </a>
                            @else
                                <button class="btn btn-sm btn-secondary" disabled>
                                    <i class="fas fa-plus mr-1"></i> Tambah Mustahik
                                </button>
                            @endif

                            <button type="button" onclick="window.print()"
                                class="btn btn-sm btn-light">
                                <i class="fas fa-print mr-1"></i> Cetak
                            </button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelMustahik"
                            class="table table-bordered table-striped table-hover table-mustahik w-100">
                            <thead>
                                <tr>
                                    <th width="40">No</th>
                                    <th>Nama Mustahik</th>
                                    <th>Alamat</th>
                                    <th>RT / RW</th>
                                    <th>Nomor HP</th>
                                    <th>Status Kupon</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Tanggal Input</th>
                                    <th width="145">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($mustahiks as $mustahik)
                                    @php
                                        $sudahDiambil = $mustahik->kuponmustahik->contains(
                                            fn ($kupon) => $kupon->status === 'sudah_diambil'
                                        );
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>
                                            <strong>{{ $mustahik->nama }}</strong>
                                        </td>

                                        <td>{{ $mustahik->alamat ?: '-' }}</td>

                                        <td class="text-center">
                                            {{ $mustahik->rt ?: '-' }}
                                            /
                                            {{ $mustahik->rw ?: '-' }}
                                        </td>

                                        <td>{{ $mustahik->nomor_hp ?: '-' }}</td>

                                        <td>
                                            @if ($sudahDiambil)
                                                <span class="badge-status badge-sudah">
                                                    <i class="fas fa-check-circle mr-1"></i>
                                                    Sudah Diambil
                                                </span>
                                            @else
                                                <span class="badge-status badge-belum">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    Belum Diambil
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $mustahik->creator->name ?? '-' }}
                                        </td>

                                        <td data-order="{{ optional($mustahik->created_at)->timestamp ?? 0 }}">
                                            {{ optional($mustahik->created_at)->format('d-m-Y H:i') ?? '-' }}
                                        </td>

                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('zakat-mustahik.edit', $mustahik->id) }}"
                                                    class="btn btn-warning btn-sm"
                                                    title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <form action="{{ route('zakat-mustahik.destroy', $mustahik->id) }}"
                                                    method="POST"
                                                    class="form-hapus-mustahik">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm"
                                                        title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if ($mustahiks->isEmpty())
                            <div class="text-center text-muted py-3">
                                Belum ada data mustahik pada periode ini.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('script')
    <script src="{{ asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(function () {
            $('#tabelMustahik').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, 'Semua']
                ],
                order: [],
                columnDefs: [
                    {
                        targets: [0, 7],
                        orderable: false,
                        searchable: false
                    }
                ],
                language: {
                    emptyTable: 'Belum ada data mustahik',
                    zeroRecords: 'Data tidak ditemukan',
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Menampilkan 0 data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    paginate: {
                        first: 'Pertama',
                        last: 'Terakhir',
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    }
                }
            });

            $('.form-hapus-mustahik').on('submit', function (e) {
                if (!confirm('Yakin ingin menghapus data mustahik ini?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
