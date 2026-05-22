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
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Data Penerima Qurban</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Qurban</a></li>
                        <li class="breadcrumb-item active">Penerima</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">

            <div class="card">
                @php
                    $totalKupon = 0;
                    $sudahDiambil = 0;
                    $belumDiambil = 0;

                    foreach ($qurban as $item) {
                        $kupon = $item->kuponqurban;

                        $totalKupon += $kupon->count();

                        $sudahDiambil += $kupon->where('status', 'sudah_diambil')->count();

                        $belumDiambil += $kupon->where('status', 'belum_diambil')->count();
                    }
                @endphp

                <div class="row">

                    <div class="col-lg-4 col-12">
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3>{{ $totalKupon }}</h3>
                                <p>Total Kupon</p>
                            </div>

                            <div class="icon">
                                <i class="fas fa-ticket-alt"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-12">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $sudahDiambil }}</h3>
                                <p>Sudah Diambil</p>
                            </div>

                            <div class="icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-12">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $belumDiambil }}</h3>
                                <p>Belum Diambil</p>
                            </div>

                            <div class="icon">
                                <i class="fas fa-times-circle"></i>
                            </div>
                        </div>
                    </div>

                </div>

                @foreach ($rwGrouped as $rw => $data)
                    <div class="col-12 mb-4">

                        <div class="card shadow-sm border-0">

                            {{-- HEADER --}}
                            <div
                                class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-map-marker-alt mr-1"></i>
                                    RW {{ $rw }}
                                </h5>

                                <span class="badge badge-light text-dark px-3 py-2">
                                    Total: {{ $data->sum('total') }} Kupon
                                </span>
                            </div>

                            {{-- BODY --}}
                            <div class="card-body">

                                <div class="row">

                                    @foreach ($data as $item)
                                        <div class="col-lg-3 col-md-4 col-6 mb-3">

                                            <div class="small-box bg-gradient-info shadow-sm">

                                                <div class="inner">
                                                    <h3 class="mb-0">{{ $item->total }}</h3>
                                                    <p class="mb-1">RT {{ $item->rt }}</p>

                                                    <small class="text-white">
                                                        <b>✔ {{ $item->sudah }}</b> diambil |
                                                        <b>✖ {{ $item->belum }}</b> belum
                                                    </small>
                                                </div>

                                                <div class="icon">
                                                    <i class="fas fa-ticket-alt"></i>
                                                </div>
                                                {{-- 🔥 tombol detail --}}
                                                <a href="{{ route('qurban.index', ['rw' => $rw, 'rt' => $item->rt]) }}#tabel"
                                                    class="small-box-footer">
                                                    Detail <i class="fas fa-arrow-circle-right"></i>
                                                </a>

                                            </div>

                                        </div>
                                    @endforeach

                                </div>

                            </div>
                        </div>

                    </div>
                @endforeach

                <form method="GET" action="{{ route('qurban.index') }}" class="mb-3">

                    <div class="row">

                        <div class="col-md-4 mb-2">
                            <input type="text" name="nama" class="form-control" placeholder="Cari Nama..."
                                value="{{ request('nama') }}">
                        </div>

                        <div class="col-md-3 mb-2">
                            <input type="number" name="rw" class="form-control" placeholder="RW"
                                value="{{ request('rw') }}">
                        </div>

                        <div class="col-md-3 mb-2">
                            <input type="number" name="rt" class="form-control" placeholder="RT"
                                value="{{ request('rt') }}">
                        </div>

                        {{-- tombol cari --}}
                        <div class="col-md-2 mb-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                Cari
                            </button>
                        </div>

                        {{-- RESET (di luar submit form logic) --}}
                        <div class="col-md-12">
                            <a href="{{ route('qurban.index') }}" class="btn btn-secondary btn-sm">
                                Reset Filter
                            </a>
                        </div>

                    </div>

                </form>

                <div class="card-header">
                    <h3 class="card-title">Data Penerima Qurban</h3>
                </div>

                @if (request('rw') || request('rt'))
                    <div class="alert alert-info d-flex justify-content-between align-items-center">

                        <div>
                            Filter aktif:

                            @if (request('rw'))
                                RW {{ request('rw') }}
                            @endif

                            @if (request('rt'))
                                | RT {{ request('rt') }}
                            @endif
                        </div>

                        <a href="{{ route('qurban.index') }}" class="btn btn-sm btn-danger">
                            Reset Filter
                        </a>

                    </div>
                @endif

                <div class="card-body">
                    <table id="tabel" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>No HP</th>
                                <th>Alamat</th>
                                <th>RT / RW</th>
                                <th>Kupon</th>
                                <th>Status</th>
                                <th>Tanggal Input</th>
                                <th>Ditambahkan Oleh</th>
                                <th>Diedit Oleh</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($qurban as $key => $row)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $row->nama }}</td>
                                    <td>{{ $row->nomor_hp }}</td>
                                    <td>{{ $row->alamat }}</td>
                                    <td>{{ $row->rt }} / {{ $row->rw }}</td>
                                    <td>{{ $row->jumlah_kupon }}</td>
                                    <td>
                                        @if ($row->status == 'Belum Diambil')
                                            <span class="badge badge-danger">Belum Diambil</span>
                                        @elseif ($row->status == 'Sebagian Diambil')
                                            <span class="badge badge-warning">Sebagian Diambil</span>
                                        @elseif ($row->status == 'Sudah Diambil')
                                            <span class="badge badge-success">Sudah Diambil</span>
                                        @endif
                                    </td>
                                    <td>{{ $row->created_at->format('d-m-Y') ?? 'N/A' }}</td>
                                    <td>{{ $row->user->name ?? 'N/A' }}</td>
                                    <td>{{ $row->updated_by_name ?? 'N/A' }}</td>

                                    <td>
                                        <a href="{{ route('qurban.edit', $row->id) }}" class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <form action="{{ route('qurban.destroy', $row->id) }}" method="POST"
                                            class="delete-form" style="display:inline;">

                                            @csrf
                                            @method('DELETE')

                                            <button type="button" class="btn btn-danger btn-sm btn-delete">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>
            </div>

        </div>
    </section>
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
        $(function() {

            $('#tabel').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
                "responsive": true,
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            if (window.location.hash === "#tabel") {
                $('html, body').animate({
                    scrollTop: $("#tabel").offset().top
                }, 600);
            }

        });
    </script>
    <script>
        $(document).on('click', '.btn-delete', function() {

            let form = $(this).closest('form');

            Swal.fire({
                title: "Yakin hapus data?",
                text: "Data yang dihapus tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {

                if (result.isConfirmed) {
                    form.submit();
                }

            });

        });
    </script>
@endsection
