@extends('admin.index')
@section('head')
    <!-- jQuery -->
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.css') }}">
@endsection
@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">

            <h3 class="mb-0">
                Validasi Manual Kupon Qurban
            </h3>

            <a href="{{ route('qurban.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>

        </div>

        {{-- CARD --}}
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    Data Kupon Qurban
                </h3>
            </div>

            <div class="card-body">

                {{-- SEARCH --}}
                <div class="mb-3">

                    <div class="input-group">

                        <input type="text" id="searchInput" class="form-control"
                            placeholder="Cari Nama / HP / RT / RW / QR Code">

                        <div class="input-group-append">

                            <button type="button" id="btn-search" class="btn btn-primary">

                                <i class="fas fa-search"></i>
                                Search

                            </button>

                        </div>

                    </div>

                </div>

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
                                <th>Scanned By</th>
                                <th>Scanned At</th>
                                <th width="150">Action</th>
                            </tr>

                        </thead>

                        <tbody>

                            @foreach ($qurban as $item)
                                @foreach ($item->kuponqurban as $k)
                                    <tr>

                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>
                                            {{ $item->nama }}
                                        </td>

                                        <td>
                                            {{ $item->nomor_hp }}
                                        </td>

                                        <td class="text-center">
                                            {{ $item->rt }} / {{ $item->rw }}
                                        </td>

                                        <td>
                                            {{ $k->qr_code }}
                                        </td>

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

                                            @if ($k->scanned_by)
                                                {{ optional($k->scannedBy)->name }}
                                            @else
                                                -
                                            @endif

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
                                                <form action="{{ route('qurban.validasi.manual.process', $k->id) }}"
                                                    method="POST" class="form-validasi">

                                                    @csrf

                                                    <button type="button" class="btn btn-success btn-sm btn-validasi">

                                                        <i class="fas fa-check"></i>
                                                        Validasi

                                                    </button>

                                                </form>
                                            @else
                                                <span class="text-muted">
                                                    Selesai
                                                </span>
                                            @endif

                                        </td>

                                    </tr>
                                @endforeach
                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection

@section('script')
    {{-- sweet ALert Github --}}
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <!-- InputMask/Moment JS -->
    <script src="{{ asset('AdminLTE/plugins/moment/moment.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    {{-- DATATABLE --}}
    <script>
        $(document).ready(function() {

            let table = $('#table-validasi').DataTable({
                responsive: true,
                pageLength: 10,
                ordering: true,
                lengthChange: true,
                autoWidth: false
            });

            // tombol search
            $('#btn-search').click(function() {

                table.search($('#searchInput').val()).draw();

            });

            // enter keyboard
            $('#searchInput').keypress(function(e) {

                if (e.which == 13) {

                    table.search($(this).val()).draw();

                }

            });

        });
    </script>

    {{-- SWEET ALERT VALIDASI --}}
    <script>
        $(document).on('click', '.btn-validasi', function() {

            let form = $(this).closest('form');

            Swal.fire({
                title: 'Validasi Pengambilan?',
                text: 'Kupon akan ditandai sudah diambil',
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
    {{-- SUCCESS --}}
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonColor: '#28a745'
            });
        </script>
    @endif


    {{-- ERROR --}}
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        </script>
    @endif
@endsection
