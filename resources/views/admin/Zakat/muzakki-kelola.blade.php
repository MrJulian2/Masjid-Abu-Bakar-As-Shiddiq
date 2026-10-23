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
                    <h3 class="card-title">
                        <i class="fas fa-users mr-1"></i>
                        Data Muzakki Zakat Fitrah
                    </h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="tabelMuzakki" class="table table-bordered table-striped table-hover w-100">
                            <thead class="text-center">
                                <tr>
                                    <th>No.</th>
                                    <th>Alamat</th>
                                    <th>Kategori</th>
                                    <th>RT</th>
                                    <th>RW</th>
                                    <th>Dibuat Oleh</th>
                                    <th>Jumlah Anggota</th>
                                    <th>Total Beras</th>
                                    <th>Total Uang</th>
                                    <th width="150">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($muzakkis as $muzakki)
                                    @php
                                        $anggota = $muzakki->details;

                                        $totalBeras = $anggota->where('jenis_zakat', 'beras')->sum('berat_beras');

                                        $totalUang = $anggota->where('jenis_zakat', 'uang')->sum('nominal_uang');
                                    @endphp

                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>

                                        <td>{{ $muzakki->alamat }}</td>

                                        <td class="text-center">
                                            @if ($muzakki->kategori === 'setempat')
                                                <span class="badge badge-success">
                                                    Warga Setempat
                                                </span>
                                            @else
                                                <span class="badge badge-info">
                                                    Warga Luar Wilayah
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            {{ $muzakki->rt ?? '-' }}
                                        </td>

                                        <td class="text-center">
                                            {{ $muzakki->rw ?? '-' }}
                                        </td>

                                        <td class="text-center">
                                            @if ($muzakki->creator)
                                                {{ $muzakki->creator->name }}
                                            @else
                                                <span class="text-muted">
                                                    Tidak diketahui
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            {{ $anggota->count() }} orang
                                        </td>

                                        <td class="text-right">
                                            {{ number_format($totalBeras, 2, ',', '.') }} Kg
                                        </td>

                                        <td class="text-right">
                                            Rp {{ number_format($totalUang, 0, ',', '.') }}
                                        </td>

                                        <td class="text-center text-nowrap">
                                            {{-- DETAIL ANGGOTA --}}
                                            <a href="{{ route('zakat-muzakki.anggota', $muzakki->id) }}"
                                                class="btn btn-info btn-sm" title="Lihat anggota">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- EDIT --}}
                                            <a href="{{ route('zakat-muzakki.edit', $muzakki->id) }}"
                                                class="btn btn-warning btn-sm" title="Edit muzakki">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            {{-- HAPUS --}}
                                            <form action="{{ route('zakat-muzakki.destroy', $muzakki->id) }}"
                                                method="POST" class="d-inline form-hapus">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="btn btn-danger btn-sm" title="Hapus muzakki">
                                                    <i class="fas fa-trash"></i>
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
            $('#tabelMuzakki').DataTable({
                responsive: true,
                autoWidth: false,
                ordering: true,
                pageLength: 10,
                lengthMenu: [10, 25, 50, 100],
                language: {
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    zeroRecords: 'Data muzakki tidak ditemukan',
                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                    infoEmpty: 'Tidak ada data',
                    infoFiltered: '(difilter dari _MAX_ total data)',
                    paginate: {
                        first: 'Awal',
                        last: 'Akhir',
                        next: 'Berikutnya',
                        previous: 'Sebelumnya'
                    }
                }
            });

            // Konfirmasi hapus menggunakan SweetAlert2
            $(document).on('submit', '.form-hapus', function(e) {
                e.preventDefault();

                const form = this;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data Muzakki beserta seluruh detail anggotanya akan dihapus!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endsection
