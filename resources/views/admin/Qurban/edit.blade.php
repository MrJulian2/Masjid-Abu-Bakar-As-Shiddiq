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
                        <button class="btn btn-primary mt-3" type="submit">
                            Update
                        </button>

                    </form>
                    <h5>Daftar QR Code</h5>

                    {{-- WRAPPER RESPONSIVE --}}
                    <div class="table-responsive">

                        <table id="qr-table" class="table table-bordered table-striped nowrap" width="100%">

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

                                        <td style="width: 100px;">
                                            {!! QrCode::size(80)->generate($k->qr_code) !!}
                                        </td>

                                        <td>
                                            @if ($k->status == 'belum_diambil')
                                                <span class="badge badge-danger">
                                                    Belum Diambil
                                                </span>
                                            @elseif ($k->status == 'sudah_diambil')
                                                <span class="badge badge-success">
                                                    Sudah Diambil
                                                </span>
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

                    </div>



                </div>
            </div>

        </div>
    </section>
@endsection

@section('script')
    {{-- SWEET ALERT --}}
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>

    {{-- MOMENT --}}
    <script src="{{ asset('AdminLTE/plugins/moment/moment.min.js') }}"></script>

    {{-- DATE RANGE PICKER --}}
    <script src="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.js') }}"></script>

    {{-- BOOTSTRAP --}}
    <script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    {{-- TEMPUSDOMINUS --}}
    <script src="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    {{-- DATATABLE --}}
    <script src="{{ asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>

    <script src="{{ asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>

    <script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('AdminLTE/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>

    <script src="{{ asset('AdminLTE/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('AdminLTE/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <style>
        #qr-table td,
        #qr-table th {
            vertical-align: middle;
            white-space: nowrap;
        }

        #qr-table svg {
            max-width: 100%;
            height: auto;
        }
    </style>

    <script>
        $(function() {

            $('#qr-table').DataTable({
                responsive: true,
                autoWidth: false,
                lengthChange: true,
                searching: true,
                ordering: true,
                paging: true,
                info: true,
            });

        });
    </script>
@endsection
