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
    <section class="content">
        <div class="container-fluid">

            <div class="card">

                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">

                        <h3 class="mb-0">Periode Qurban</h3>

                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
                            <i class="fas fa-plus"></i> Tambah Periode
                        </button>

                    </div>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table id="periodeTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tahun</th>
                                    <th>Nama Periode</th>
                                    <th>Status</th>
                                    <th width="250">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($periodes ?? [] as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->tahun }}</td>
                                        <td>{{ $item->nama }}</td>

                                        <td>
                                            @if ($item->aktif)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            @endif
                                        </td>

                                        <td>

                                            @if (!$item->aktif)
                                                <form action="{{ route('qurban-periode.aktifkan', $item->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="btn btn-success btn-sm">Aktifkan</button>
                                                </form>
                                            @endif

                                            <button class="btn btn-warning btn-sm" data-toggle="modal"
                                                data-target="#edit{{ $item->id }}">
                                                Edit
                                            </button>

                                            @if (!$item->aktif)
                                                <form action="{{ route('qurban-periode.destroy', $item->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-danger btn-sm"
                                                        onclick="return confirm('Yakin hapus periode ini?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">
                                            Belum ada data periode
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>
    </section>

    {{-- MODAL TAMBAH --}}
    <div class="modal fade" id="modalTambah">
        <div class="modal-dialog">
            <form action="{{ route('qurban-periode.store') }}" method="POST">
                @csrf

                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Periode</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">

                        <div class="form-group">
                            <label>Tahun</label>
                            <input type="number" name="tahun" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label>Nama Periode</label>
                            <input type="text" name="nama" class="form-control" required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    @forelse($periodes ?? [] as $item)
        <div class="modal fade" id="edit{{ $item->id }}">
            <div class="modal-dialog">

                <form action="{{ route('qurban-periode.update', $item->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Periode</h5>
                            <button type="button" class="close" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">

                            <div class="form-group">
                                <label>Tahun</label>
                                <input type="number" name="tahun" value="{{ $item->tahun }}" class="form-control"
                                    required>
                            </div>

                            <div class="form-group">
                                <label>Nama Periode</label>
                                <input type="text" name="nama" value="{{ $item->nama }}" class="form-control"
                                    required>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>

                    </div>

                </form>

            </div>
        </div>
    @empty
    @endforelse
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

    <script>
        $(document).ready(function() {

            $('#periodeTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    zeroRecords: "Data tidak ditemukan",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: {
                        first: "Awal",
                        last: "Akhir",
                        next: "→",
                        previous: "←"
                    }
                }
            });

        });
    </script>
@endsection
