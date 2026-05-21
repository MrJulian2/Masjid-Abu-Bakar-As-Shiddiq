@extends('admin.index')

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    Edit Data Penerima Qurban
                </h3>
            </div>

            <div class="card-body">

                <form action="{{ route('qurban.update', $qurban->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- NAMA --}}
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text"
                               name="nama"
                               class="form-control"
                               value="{{ $qurban->nama }}">
                    </div>

                    {{-- NO HP --}}
                    <div class="form-group">
                        <label>No HP</label>
                        <input type="text"
                               name="nomor_hp"
                               class="form-control"
                               value="{{ $qurban->nomor_hp }}">
                    </div>

                    {{-- ALAMAT --}}
                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat"
                                  rows="3"
                                  class="form-control">{{ $qurban->alamat }}</textarea>
                    </div>

                    {{-- RT --}}
                    <div class="form-group">
                        <label>RT</label>
                        <input type="text"
                               name="rt"
                               class="form-control"
                               value="{{ $qurban->rt }}">
                    </div>

                    {{-- RW --}}
                    <div class="form-group">
                        <label>RW</label>
                        <input type="text"
                               name="rw"
                               class="form-control"
                               value="{{ $qurban->rw }}">
                    </div>

                    {{-- JUMLAH KUPON --}}
                    <div class="form-group">
                        <label>Jumlah Kupon</label>
                        <input type="number"
                               name="jumlah_kupon"
                               class="form-control"
                               value="{{ count($kupon) }}"
                               min="1">
                    </div>

                    <hr>

                    {{-- TABLE --}}
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">Daftar QR Code</h5>
                    </div>

                    <div class="table-responsive">

                        <table id="qr-table"
                               class="table table-bordered table-striped table-hover nowrap"
                               width="100%">

                            <thead class="thead-dark">
                                <tr>
                                    <th width="40">No</th>
                                    <th>Kode QR</th>
                                    <th width="120">QR Code</th>
                                    <th width="140">Status</th>
                                    <th>DiScan Oleh</th>
                                    <th width="170">DiScan Pada</th>
                                </tr>
                            </thead>

                            <tbody>

                                @foreach ($kupon as $key => $k)
                                    <tr>

                                        {{-- NO --}}
                                        <td class="text-center">
                                            {{ $key + 1 }}
                                        </td>

                                        {{-- KODE QR --}}
                                        <td>
                                            <span class="font-weight-bold">
                                                {{ $k->qr_code }}
                                            </span>
                                        </td>

                                        {{-- QR --}}
                                        <td class="text-center">

                                            <div class="qr-wrapper">
                                                {!! QrCode::size(70)->generate($k->qr_code) !!}
                                            </div>

                                        </td>

                                        {{-- STATUS --}}
                                        <td class="text-center">

                                            @if ($k->status == 'belum_diambil')

                                                <span class="badge badge-danger px-3 py-2">
                                                    Belum Diambil
                                                </span>

                                            @elseif ($k->status == 'sudah_diambil')

                                                <span class="badge badge-success px-3 py-2">
                                                    Sudah Diambil
                                                </span>

                                            @endif

                                        </td>

                                        {{-- SCANNED BY --}}
                                        <td>

                                            @if ($k->scanned_by)
                                                {{ $k->scannedBy->name }}
                                            @else
                                                -
                                            @endif

                                        </td>

                                        {{-- SCANNED AT --}}
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

                    {{-- BUTTON --}}
                    <div class="mt-3">

                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-save"></i>
                            Update
                        </button>

                        <a href="{{ route('qurban.index') }}"
                           class="btn btn-secondary">
                            Kembali
                        </a>

                    </div>

                </form>

            </div>
        </div>

    </div>
</section>
@endsection


@section('script')

{{-- SWEET ALERT --}}
<script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>

{{-- DATATABLES --}}
<script src="{{ asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

<style>

/* =========================
   TABLE FIX
========================= */
#qr-table {
    width: 100% !important;
}

/* =========================
   TABLE HEAD
========================= */
#qr-table thead th {
    text-align: center;
    vertical-align: middle !important;
    white-space: nowrap;
    font-size: 13px;
}

/* =========================
   TABLE BODY
========================= */
#qr-table tbody td {
    vertical-align: middle !important;
    font-size: 13px;
}

/* =========================
   QR FIX
========================= */
.qr-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
}

.qr-wrapper svg {
    width: 70px !important;
    height: 70px !important;
}

/* =========================
   DATATABLE RESPONSIVE
========================= */
.dataTables_wrapper {
    width: 100%;
}

.dataTables_scrollBody {
    overflow-x: auto !important;
}

/* =========================
   MOBILE
========================= */
@media screen and (max-width: 768px) {

    #qr-table thead th,
    #qr-table tbody td {
        font-size: 12px;
    }

    .qr-wrapper svg {
        width: 55px !important;
        height: 55px !important;
    }
}

</style>

<script>

$(document).ready(function () {

    $('#qr-table').DataTable({

        responsive: true,
        autoWidth: false,
        scrollX: true,

        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            zeroRecords: "Data tidak ditemukan",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
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