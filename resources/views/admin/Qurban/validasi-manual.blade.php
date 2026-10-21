
@extends('admin.index')

@section('head')
    <!-- jQuery -->
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">
                Validasi Manual Kupon Qurban - || -
                {{ $periodeAktif->nama ?? 'Belum Ada Periode Aktif' }}
            </h3>

            <a href="{{ route('qurban.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>

        {{-- INFORMASI PERIODE --}}
        @if (!$periodeAktif)
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <h5>
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Periode Qurban Belum Aktif
                </h5>

                <p class="mb-0">
                    Saat ini belum ada periode Qurban yang aktif.
                    Data kupon tidak dapat ditampilkan dan validasi pengambilan
                    kupon belum dapat dilakukan. Silakan aktifkan periode Qurban
                    terlebih dahulu melalui menu Periode Qurban.
                </p>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @else
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle mr-1"></i>
                Anda sedang melakukan validasi kupon untuk periode
                <strong>{{ $periodeAktif->nama }}</strong>.
                Pastikan kupon sudah benar sebelum melakukan validasi pengambilan.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        {{-- CARD --}}
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">
                    <i class="fas fa-ticket-alt mr-1"></i>
                    Data Kupon Qurban
                </h3>
            </div>

            <div class="card-body">

                @if ($periodeAktif)

                    {{-- FILTER --}}
                    <form method="GET" action="{{ route('qurban.validasi.manual') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <input
                                    type="text"
                                    name="nama"
                                    class="form-control"
                                    placeholder="Cari Nama..."
                                    value="{{ request('nama') }}"
                                >
                            </div>

                            <div class="col-md-3 mb-2">
                                <input
                                    type="number"
                                    name="rw"
                                    class="form-control"
                                    placeholder="RW"
                                    value="{{ request('rw') }}"
                                >
                            </div>

                            <div class="col-md-3 mb-2">
                                <input
                                    type="number"
                                    name="rt"
                                    class="form-control"
                                    placeholder="RT"
                                    value="{{ request('rt') }}"
                                >
                            </div>

                            <div class="col-md-2 mb-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fas fa-search mr-1"></i> Cari
                                </button>
                            </div>

                            <div class="col-md-12">
                                <a href="{{ route('qurban.validasi.manual') }}"
                                    class="btn btn-secondary btn-sm">
                                    <i class="fas fa-sync-alt mr-1"></i> Reset Filter
                                </a>
                            </div>
                        </div>
                    </form>

                    {{-- TABLE --}}
                    <div class="table-responsive">
                        <table id="table-validasi" class="table table-bordered table-striped">
                            <thead class="text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>No HP</th>
                                    <th>RT/RW</th>
                                    <th>QR Code</th>
                                    <th>Status</th>
                                    <th>Dibuat pada</th>
                                    <th>Scanned By</th>
                                    <th>Scanned At</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($qurban as $item)
                                    @foreach ($item->kuponqurban as $k)
                                        <tr>
                                            <td class="text-center"></td>

                                            <td>{{ $item->nama }}</td>

                                            <td>{{ $item->nomor_hp }}</td>

                                            <td class="text-center">
                                                {{ $item->rt }} / {{ $item->rw }}
                                            </td>

                                            <td>{{ $k->qr_code }}</td>

                                            <td class="text-center">
                                                @if ($k->status == 'sudah_diambil')
                                                    <span class="badge badge-success">
                                                        Sudah Diambil
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        Belum Diambil
                                                    </span>
                                                @endif
                                            </td>

                                            <td>
                                                {{ $k->created_at
                                                    ? $k->created_at->format('d-m-Y H:i:s')
                                                    : 'N/A' }}
                                            </td>

                                            <td>
                                                {{ $k->scanned_by
                                                    ? (optional($k->scannedBy)->name ?? '-')
                                                    : '-' }}
                                            </td>

                                            <td>
                                                @if ($k->scanned_at)
                                                    {{ \Carbon\Carbon::parse($k->scanned_at)->format('d M Y H:i') }}
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                @if ($k->status != 'sudah_diambil')
                                                    <form
                                                        action="{{ route('qurban.validasi.manual.process', $k->id) }}"
                                                        method="POST"
                                                        class="form-validasi"
                                                    >
                                                        @csrf

                                                        <button type="button"
                                                            class="btn btn-success btn-sm btn-validasi">
                                                            <i class="fas fa-check"></i>
                                                            Validasi
                                                        </button>
                                                    </form>
                                                @else
                                                    <span class="text-muted">Selesai</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center text-muted">
                                            Belum ada data Qurban pada periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                @else

                    {{-- DATA KOSONG KETIKA TIDAK ADA PERIODE --}}
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-warning mb-3"></i>

                        <h5>Data Kupon Tidak Tersedia</h5>

                        <p class="text-muted mb-0">
                            Aktifkan periode Qurban terlebih dahulu untuk
                            menampilkan data dan melakukan validasi kupon.
                        </p>
                    </div>

                @endif
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- SweetAlert -->
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- DataTables -->
    <script src="{{ asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        $(document).ready(function () {
            if ($('#table-validasi').length) {
                let table = $('#table-validasi').DataTable({
                    responsive: true,
                    pageLength: 10,
                    ordering: true,
                    lengthChange: true,
                    autoWidth: false,
                    language: {
                        emptyTable: 'Belum ada data kupon Qurban.',
                        zeroRecords: 'Data tidak ditemukan.',
                        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                        infoEmpty: 'Tidak ada data',
                        search: 'Cari:',
                        lengthMenu: 'Tampilkan _MENU_ data',
                        paginate: {
                            first: 'Pertama',
                            last: 'Terakhir',
                            next: 'Berikutnya',
                            previous: 'Sebelumnya'
                        }
                    },
                    columnDefs: [
                        {
                            targets: 0,
                            searchable: false,
                            orderable: false
                        }
                    ],
                    drawCallback: function () {
                        let api = this.api();
                        let start = api.page.info().start;

                        api.column(0, { page: 'current' }).nodes().each(function (cell, i) {
                            cell.innerHTML = start + i + 1;
                        });
                    }
                });
            }
        });

        // KONFIRMASI VALIDASI
        $(document).on('click', '.btn-validasi', function () {
            let form = $(this).closest('form');

            Swal.fire({
                title: 'Validasi Pengambilan?',
                text: 'Kupon akan ditandai sudah diambil.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Validasi',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    </script>

    {{-- NOTIFIKASI SUKSES --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                confirmButtonColor: '#28a745'
            });
        </script>
    @endif

    {{-- NOTIFIKASI ERROR --}}
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json(session('error')),
                confirmButtonColor: '#d33'
            });
        </script>
    @endif
@endsection
