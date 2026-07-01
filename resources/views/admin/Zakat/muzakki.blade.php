@extends('admin.index')

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card">

                <div class="card-header">
                    <h3>Input Muzakki Zakat Fitrah</h3>
                </div>
                <form action="{{ route('zakat-muzakki.store') }}" method="POST">
                    @csrf

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Kategori Muzakki</label>

                                    <select name="kategori" id="kategori" class="form-control">
                                        <option value="setempat">Warga Setempat</option>
                                        <option value="luar">Warga Luar Wilayah</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Alamat</label>
                                    <textarea name="alamat" class="form-control" rows="2" required></textarea>
                                </div>
                            </div>

                            <div class="col-md-3 rt-wrapper">
                                <div class="form-group">
                                    <label>RT</label>
                                    <input type="number" name="rt" id="rt" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-3 rw-wrapper">
                                <div class="form-group">
                                    <label>RW</label>
                                    <input type="number" name="rw" id="rw" class="form-control" required>
                                </div>
                            </div>

                        </div>

                        <hr>

                        <div class="d-flex justify-content-between align-items-center mb-3">

                            <h5 class="mb-0">
                                Daftar Anggota Muzakki
                            </h5>

                            <button type="button" class="btn btn-success btn-sm" id="btnTambah">
                                <i class="fas fa-plus"></i>
                                Tambah Anggota
                            </button>

                        </div>

                        <div id="anggotaWrapper">

                            <div class="row anggota-item mb-3">

                                <div class="col-md-4">
                                    <label>Nama</label>
                                    <input placeholder="Masukkan nama" type="text" name="nama[]" class="form-control" required>
                                </div>

                                <div class="col-md-3">
                                    <label>Jenis Zakat</label>

                                    <select name="jenis_zakat[]" class="form-control jenis-zakat">

                                        <option value="beras">Beras</option>
                                        <option value="uang">Uang</option>

                                    </select>
                                </div>

                                <div class="col-md-4 beras-wrapper d-none">

                                    <label>Berat Beras / Jiwa</label>

                                    <select name="berat_beras[]" class="form-control">
                                        @foreach ($opsiBeras as $item)
                                            <option value="{{ $item->nilai_beras }}">
                                                {{ $item->nilai_beras }} Kg
                                            </option>
                                        @endforeach
                                    </select>

                                </div>

                                <div class="col-md-4 uang-wrapper d-none">

                                    <label>Nominal</label>

                                    <select name="nominal_uang[]" class="form-control" disabled>

                                        @foreach ($opsiUang as $item)
                                            <option value="{{ $item->nilai_uang }}">
                                                Rp {{ number_format($item->nilai_uang, 0, ',', '.') }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>

                                <div class="col-md-1 d-flex align-items-end">

                                    <button type="button" class="btn btn-danger btnRemove">

                                        <i class="fas fa-trash"></i>

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Simpan
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </section>
@endsection
<script>
    let opsiBeras = `
        @foreach ($opsiBeras as $item)
            <option value="{{ $item->nilai_beras }}">
                {{ $item->nilai_beras }} Kg
            </option>
        @endforeach
    `;

    let opsiUang = `
        @foreach ($opsiUang as $item)
            <option value="{{ $item->nilai_uang }}">
                Rp {{ number_format($item->nilai_uang, 0, ',', '.') }}
            </option>
        @endforeach
    `;
</script>

@section('script')
    <script>
        $(function() {

            // ==========================
            // KATEGORI MUZAKKI
            // ==========================
            $('#kategori').on('change', function() {

                let kategori = $(this).val();

                if (kategori === 'luar') {

                    $('#rt')
                        .prop('required', false)
                        .prop('disabled', true)
                        .val('');

                    $('#rw')
                        .prop('required', false)
                        .prop('disabled', true)
                        .val('');

                } else {

                    $('#rt')
                        .prop('required', true)
                        .prop('disabled', false);

                    $('#rw')
                        .prop('required', true)
                        .prop('disabled', false);

                }

            });

            // trigger awal
            $('#kategori').trigger('change');

            // ==========================
            // TEMPLATE ANGGOTA
            // ==========================
            function anggotaTemplate() {

                return `
        <div class="row anggota-item mb-3">

            <div class="col-md-4">
                <label>Nama</label>
                <input
                    type="text"
                    name="nama[]"
                    class="form-control"
                    placeholder="Nama Anggota"
                    required>
            </div>

            <div class="col-md-3">
                <label>Jenis Zakat</label>
                <select
                    name="jenis_zakat[]"
                    class="form-control jenis-zakat">

                    <option value="beras">Beras</option>
                    <option value="uang">Uang</option>

                </select>
            </div>

            <div class="col-md-4 beras-wrapper">

                <label>Berat Beras / Jiwa</label>

                <select
                    name="berat_beras[]"
                    class="form-control">

                    ${opsiBeras}

                </select>

            </div>

            <div class="col-md-4 uang-wrapper d-none">

                <label>Nominal</label>

                <select
                    name="nominal_uang[]"
                    class="form-control">

                    ${opsiUang}

                </select>

            </div>

            <div class="col-md-1 d-flex align-items-end">

                <button
                    type="button"
                    class="btn btn-danger btnRemove">

                    <i class="fas fa-trash"></i>

                </button>

            </div>

        </div>
        `;
            }

            // ==========================
            // TAMBAH ANGGOTA
            // ==========================
            $('#btnTambah').on('click', function() {

                $('#anggotaWrapper').append(
                    anggotaTemplate()
                );

            });

            // ==========================
            // HAPUS ANGGOTA
            // ==========================
            $(document).on('click', '.btnRemove', function() {

                if ($('.anggota-item').length > 1) {

                    $(this)
                        .closest('.anggota-item')
                        .remove();

                }

            });

            // ==========================
            // BERAS / UANG
            // ==========================
            $(document).on('change', '.jenis-zakat', function() {

                let row = $(this).closest('.anggota-item');

                if ($(this).val() === 'uang') {

                    row.find('.beras-wrapper')
                        .addClass('d-none');

                    row.find('.uang-wrapper')
                        .removeClass('d-none');

                    row.find('select[name="berat_beras[]"]')
                        .prop('disabled', true);

                    row.find('select[name="nominal_uang[]"]')
                        .prop('disabled', false);

                } else {

                    row.find('.uang-wrapper')
                        .addClass('d-none');

                    row.find('.beras-wrapper')
                        .removeClass('d-none');

                    row.find('select[name="berat_beras[]"]')
                        .prop('disabled', false);

                    row.find('select[name="nominal_uang[]"]')
                        .prop('disabled', true);

                }

            });

            // trigger row pertama
            $('.jenis-zakat').trigger('change');

        });
    </script>
@endsection
