
@extends('admin.index')

@section('content')
    <section class="content">
        <div class="container-fluid">

            <div class="card">
                <div class="card-header">
                    <h3>Edit Muzakki Zakat Fitrah</h3>
                </div>

                <form action="{{ route('zakat-muzakki.update', $muzakki->id) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="card-body">

                        {{-- KATEGORI MUZAKKI --}}
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Kategori Muzakki</label>

                                    <select name="kategori"
                                        id="kategori"
                                        class="form-control"
                                        required>
                                        <option value="setempat"
                                            {{ old('kategori', $muzakki->kategori) == 'setempat' ? 'selected' : '' }}>
                                            Warga Setempat
                                        </option>

                                        <option value="luar"
                                            {{ old('kategori', $muzakki->kategori) == 'luar' ? 'selected' : '' }}>
                                            Warga Luar Wilayah
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- ALAMAT, RT, RW --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Alamat</label>

                                    <textarea name="alamat"
                                        class="form-control"
                                        rows="2"
                                        required>{{ old('alamat', $muzakki->alamat) }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-3 rt-wrapper">
                                <div class="form-group">
                                    <label>RT</label>

                                    <input type="number"
                                        name="rt"
                                        id="rt"
                                        class="form-control"
                                        value="{{ old('rt', $muzakki->rt) }}">
                                </div>
                            </div>

                            <div class="col-md-3 rw-wrapper">
                                <div class="form-group">
                                    <label>RW</label>

                                    <input type="number"
                                        name="rw"
                                        id="rw"
                                        class="form-control"
                                        value="{{ old('rw', $muzakki->rw) }}">
                                </div>
                            </div>
                        </div>

                        <hr>

                        {{-- DAFTAR ANGGOTA --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Daftar Anggota Muzakki</h5>

                            <button type="button"
                                class="btn btn-success btn-sm"
                                id="btnTambah">
                                <i class="fas fa-plus"></i>
                                Tambah Anggota
                            </button>
                        </div>

                        <div id="anggotaWrapper">

                            @forelse ($muzakki->details as $detail)
                                <div class="row anggota-item mb-3">

                                    {{-- ID ANGGOTA LAMA --}}
                                    <input type="hidden"
                                        name="detail_id[]"
                                        value="{{ old('detail_id.' . $loop->index, $detail->id) }}">

                                    <div class="col-md-4">
                                        <label>Nama</label>

                                        <input type="text"
                                            name="nama[]"
                                            class="form-control"
                                            placeholder="Masukkan nama"
                                            value="{{ old('nama.' . $loop->index, $detail->nama) }}"
                                            required>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Jenis Zakat</label>

                                        <select name="jenis_zakat[]"
                                            class="form-control jenis-zakat"
                                            required>
                                            <option value="beras"
                                                {{ old('jenis_zakat.' . $loop->index, $detail->jenis_zakat) == 'beras' ? 'selected' : '' }}>
                                                Beras
                                            </option>

                                            <option value="uang"
                                                {{ old('jenis_zakat.' . $loop->index, $detail->jenis_zakat) == 'uang' ? 'selected' : '' }}>
                                                Uang
                                            </option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 beras-wrapper">
                                        <label>Berat Beras / Jiwa</label>

                                        <select name="berat_beras[]"
                                            class="form-control">
                                            @foreach ($opsiBeras as $item)
                                                <option value="{{ $item->nilai_beras }}"
                                                    {{ (string) old('berat_beras.' . $loop->index, $detail->berat_beras) === (string) $item->nilai_beras ? 'selected' : '' }}>
                                                    {{ $item->nilai_beras }} Kg
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 uang-wrapper">
                                        <label>Nominal</label>

                                        <select name="nominal_uang[]"
                                            class="form-control">
                                            @foreach ($opsiUang as $item)
                                                <option value="{{ $item->nilai_uang }}"
                                                    {{ (string) old('nominal_uang.' . $loop->index, $detail->nominal_uang) === (string) $item->nilai_uang ? 'selected' : '' }}>
                                                    Rp {{ number_format($item->nilai_uang, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-1 d-flex align-items-end mb-3">
                                        <button type="button"
                                            class="btn btn-danger btnRemove"
                                            title="Hapus anggota">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                </div>
                            @empty
                                <div class="row anggota-item mb-3">

                                    {{-- KOSONG = ANGGOTA BARU --}}
                                    <input type="hidden"
                                        name="detail_id[]"
                                        value="">

                                    <div class="col-md-4">
                                        <label>Nama</label>
                                        <input type="text"
                                            name="nama[]"
                                            class="form-control"
                                            placeholder="Masukkan nama"
                                            required>
                                    </div>

                                    <div class="col-md-3">
                                        <label>Jenis Zakat</label>
                                        <select name="jenis_zakat[]"
                                            class="form-control jenis-zakat"
                                            required>
                                            <option value="beras">Beras</option>
                                            <option value="uang">Uang</option>
                                        </select>
                                    </div>

                                    <div class="col-md-4 beras-wrapper">
                                        <label>Berat Beras / Jiwa</label>
                                        <select name="berat_beras[]" class="form-control">
                                            @foreach ($opsiBeras as $item)
                                                <option value="{{ $item->nilai_beras }}">
                                                    {{ $item->nilai_beras }} Kg
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4 uang-wrapper">
                                        <label>Nominal</label>
                                        <select name="nominal_uang[]" class="form-control">
                                            @foreach ($opsiUang as $item)
                                                <option value="{{ $item->nilai_uang }}">
                                                    Rp {{ number_format($item->nilai_uang, 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-1 d-flex align-items-end mb-3">
                                        <button type="button"
                                            class="btn btn-danger btnRemove">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>

                                </div>
                            @endforelse

                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i>
                            Update
                        </button>

                        <a href="{{ route('zakat-muzakki.kelola') }}"
                            class="btn btn-secondary">
                            Batal
                        </a>
                    </div>

                </form>
            </div>

        </div>
    </section>
@endsection

@section('script')
    <script>
        $(function () {

            // ==========================
            // OPSI BERAS DAN UANG
            // ==========================
            const opsiBeras = `
                @foreach ($opsiBeras as $item)
                    <option value="{{ $item->nilai_beras }}">
                        {{ $item->nilai_beras }} Kg
                    </option>
                @endforeach
            `;

            const opsiUang = `
                @foreach ($opsiUang as $item)
                    <option value="{{ $item->nilai_uang }}">
                        Rp {{ number_format($item->nilai_uang, 0, ',', '.') }}
                    </option>
                @endforeach
            `;

            // ==========================
            // KATEGORI MUZAKKI
            // ==========================
            function aturKategori() {
                const luar = $('#kategori').val() === 'luar';

                $('#rt, #rw')
                    .prop('required', !luar)
                    .prop('disabled', luar);

                $('.rt-wrapper, .rw-wrapper')
                    .toggleClass('d-none', luar);
            }

            $('#kategori').on('change', aturKategori);
            aturKategori();

            // ==========================
            // JENIS ZAKAT BERAS / UANG
            // ==========================
            function aturJenisZakat(row) {
                const jenis = row.find('.jenis-zakat').val();
                const beras = jenis === 'beras';

                row.find('.beras-wrapper')
                    .toggleClass('d-none', !beras);

                row.find('.uang-wrapper')
                    .toggleClass('d-none', beras);

                // Tetap aktif supaya indeks array tidak bergeser.
                // Controller akan memilih nilai berdasarkan jenis zakat.
                row.find('select[name="berat_beras[]"], select[name="nominal_uang[]"]')
                    .prop('disabled', false);
            }

            $(document).on('change', '.jenis-zakat', function () {
                aturJenisZakat($(this).closest('.anggota-item'));
            });

            $('.anggota-item').each(function () {
                aturJenisZakat($(this));
            });

            // ==========================
            // TEMPLATE ANGGOTA BARU
            // ==========================
            function anggotaTemplate() {
                return `
                    <div class="row anggota-item mb-3">

                        <input type="hidden"
                            name="detail_id[]"
                            value="">

                        <div class="col-md-4">
                            <label>Nama</label>
                            <input type="text"
                                name="nama[]"
                                class="form-control"
                                placeholder="Masukkan nama"
                                required>
                        </div>

                        <div class="col-md-3">
                            <label>Jenis Zakat</label>
                            <select name="jenis_zakat[]"
                                class="form-control jenis-zakat"
                                required>
                                <option value="beras">Beras</option>
                                <option value="uang">Uang</option>
                            </select>
                        </div>

                        <div class="col-md-4 beras-wrapper">
                            <label>Berat Beras / Jiwa</label>
                            <select name="berat_beras[]" class="form-control">
                                ${opsiBeras}
                            </select>
                        </div>

                        <div class="col-md-4 uang-wrapper">
                            <label>Nominal</label>
                            <select name="nominal_uang[]" class="form-control">
                                ${opsiUang}
                            </select>
                        </div>

                        <div class="col-md-1 d-flex align-items-end mb-3">
                            <button type="button"
                                class="btn btn-danger btnRemove"
                                title="Hapus anggota">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                    </div>
                `;
            }

            // ==========================
            // TAMBAH ANGGOTA
            // ==========================
            $('#btnTambah').on('click', function () {
                const row = $(anggotaTemplate());

                $('#anggotaWrapper').append(row);
                aturJenisZakat(row);
            });

            // ==========================
            // HAPUS ANGGOTA
            // ==========================
            $(document).on('click', '.btnRemove', function () {
                if ($('.anggota-item').length > 1) {
                    $(this).closest('.anggota-item').remove();
                } else {
                    alert('Minimal harus ada satu anggota Muzakki.');
                }
            });

        });
    </script>
@endsection
