@extends('admin.index')

@section('head')
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Master Opsi Zakat Fitrah</h3>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#modalTambah">
                            <i class="fas fa-plus"></i> Tambah Opsi
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="zakatopsiTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th width="50">No</th>
                                    <th width="120">Jenis</th>
                                    <th>Nilai</th>
                                    <th width="100">Status</th>
                                    <th width="250">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($opsis as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ ucfirst($item->jenis) }}</td>
                                        <td>
                                            @if ($item->jenis == 'beras')
                                                {{ $item->nilai_beras }} Kg
                                            @else
                                                Rp {{ number_format($item->nilai_uang, 0, ',', '.') }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->aktif)
                                                <span class="badge badge-success">Aktif</span>
                                            @else
                                                <span class="badge badge-secondary">Nonaktif</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->aktif == false)
                                                <form action="{{ route('zakat-opsi.aktifkan', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="btn btn-success btn-sm">Aktifkan</button>
                                                </form>
                                            @else
                                                <form action="{{ route('zakat-opsi.nonaktifkan', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button class="btn btn-warning btn-sm">Nonaktifkan</button>
                                                </form>
                                            @endif

                                            <button class="btn btn-warning btn-sm" data-toggle="modal"
                                                data-target="#edit{{ $item->id }}">
                                                Edit
                                            </button>

                                            <form action="{{ route('zakat-opsi.destroy', $item->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Yakin hapus data ini ?')">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data opsi zakat</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </section>


    {{-- =============================== --}}
    {{-- MODAL TAMBAH                    --}}
    {{-- Semua field pakai class seragam --}}
    {{-- selectJenis, wrapBeras, wrapUang--}}
    {{-- =============================== --}}
    <div class="modal fade zakatModal" id="modalTambah">
        <div class="modal-dialog">
            <form action="{{ route('zakat-opsi.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Opsi Zakat</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>Jenis</label>
                            <select name="jenis" class="form-control" onchange="toggleZakat(this)">
                                <option value="beras">Beras</option>
                                <option value="uang">Uang</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Berat Beras (Kg)</label>
                            <input type="number" step="0.01" name="nilai_beras" class="form-control">
                        </div>

                        <div class="form-group">
                            <label>Nominal Uang</label>
                            <input type="number" name="nilai_uang" class="form-control" readonly style="background:#e9ecef;">
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


    {{-- =============================== --}}
    {{-- MODAL EDIT (per item)           --}}
    {{-- =============================== --}}
    @if ($opsis->isNotEmpty())
        @foreach ($opsis as $item)
            <div class="modal fade zakatModal" id="edit{{ $item->id }}">
                <div class="modal-dialog">
                    <form action="{{ route('zakat-opsi.update', $item->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Opsi Zakat</h5>
                                <button type="button" class="close" data-dismiss="modal">
                                    <span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">

                                <div class="form-group">
                                    <label>Jenis</label>
                                    <select name="jenis" class="form-control" onchange="toggleZakat(this)">
                                        <option value="beras" {{ $item->jenis == 'beras' ? 'selected' : '' }}>Beras</option>
                                        <option value="uang"  {{ $item->jenis == 'uang'  ? 'selected' : '' }}>Uang</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Berat Beras (Kg)</label>
                                    <input type="number" step="0.01" name="nilai_beras"
                                        value="{{ $item->nilai_beras }}" class="form-control"
                                        @if($item->jenis == 'uang') readonly style="background:#e9ecef;" @endif>
                                </div>

                                <div class="form-group">
                                    <label>Nominal Uang</label>
                                    <input type="number" name="nilai_uang"
                                        value="{{ $item->nilai_uang }}" class="form-control"
                                        @if($item->jenis == 'beras') readonly style="background:#e9ecef;" @endif>
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
        @endforeach
    @endif

@endsection


@section('script')
    <script src="{{ asset('vendor/sweetalert/sweetalert.all.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('AdminLTE/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <script>
        // Global function - dipanggil langsung dari onchange di HTML
        // Tidak bergantung pada jQuery ready / event delegation
        function toggleZakat(selectEl) {
            var jenis  = selectEl.value;
            var wrap   = selectEl.closest('.modal-content');
            var inBeras = wrap.querySelector('input[name="nilai_beras"]');
            var inUang  = wrap.querySelector('input[name="nilai_uang"]');

            if (jenis === 'uang') {
                inBeras.value    = '';
                inBeras.readOnly = true;
                inBeras.style.background = '#e9ecef';

                inUang.readOnly = false;
                inUang.style.background = '';
            } else {
                inUang.value    = '';
                inUang.readOnly = true;
                inUang.style.background = '#e9ecef';

                inBeras.readOnly = false;
                inBeras.style.background = '';
            }
        }

        $(document).ready(function () {

            // ==========================
            // DATATABLE
            // ==========================
            $('#zakatopsiTable').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 10
            });

            // ============================================================
            // TOGGLE FIELD BERAS / UANG
            // Cari wrapper terdekat .zakatModal dari select yang berubah
            // ============================================================
            // kosongkan jQuery untuk toggle - pakai pure JS agar tidak bentrok
            // dengan jQuery/Bootstrap yang di-load ulang oleh AdminLTE



        });
    </script>
@endsection