@extends('admin.index')

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h3>Edit Data Penerima Qurban</h3>
                </div>

                <div class="card-body">

                    <form action="{{ route('qurban.update', $qurban->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" name="nama" class="form-control" value="{{ $qurban->nama }}">
                        </div>

                        <div class="form-group">
                            <label>No HP</label>
                            <input type="text" name="nomor_hp" class="form-control" value="{{ $qurban->nomor_hp }}">
                        </div>

                        <div class="form-group">
                            <label>Alamat</label>
                            <textarea name="alamat" class="form-control">{{ $qurban->alamat }}</textarea>
                        </div>

                        <div class="form-group">
                            <label>RT</label>
                            <input type="text" name="rt" class="form-control" value="{{ $qurban->rt }}">
                        </div>

                        <div class="form-group">
                            <label>RW</label>
                            <input type="text" name="rw" class="form-control" value="{{ $qurban->rw }}">
                        </div>

                        <div class="form-group">
                            <label>Jumlah Kupon</label>
                            <input type="number" name="jumlah_kupon" class="form-control" value="{{ count($kupon) }}"
                                min="1">
                        </div>

                        <hr>

                        <h5>Daftar QR Code</h5>

                        <table id="qr-table" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode QR</th>
                                    <th>QR Code</th>
                                    <th>Status</th>
                                    <th>DiScan Oleh</th>
                                    <th>DiScan Pada</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($kupon as $key => $k)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $k->qr_code }}</td>
                                        <td>{!! QrCode::size(150)->generate($k->qr_code) !!}</td>
                                        <td>
                                            @if ($k->status == 'belum_diambil')
                                                <span class="badge badge-danger">Belum Diambil</span>
                                            @elseif ($k->status == 'sudah_diambil')
                                                <span class="badge badge-success">Sudah Diambil</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($k->scanned_by)
                                                {{ $k->scannedBy->name }}
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
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button class="btn btn-primary" type="submit">
                            Update
                        </button>

                    </form>

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

            $('#qr-table').DataTable({
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
@endsection
