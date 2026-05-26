@extends('admin.index')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">

        <h3 class="mb-0">Cetak Kupon Qurban</h3>

        <div class="d-flex gap-2">

            <a href="{{ route('qurban.laporan.pdf') }}" class="btn btn-danger">
                <i class="fas fa-file-pdf"></i> Export PDF
            </a>

            <button type="button" onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Semua
            </button>

            <button type="submit" form="formPrintSelected" class="btn btn-success">
                <i class="fas fa-check"></i> Cetak Terpilih
            </button>

            <a href="{{ route('qurban.index') }}" class="btn btn-secondary">
                Kembali
            </a>

        </div>

    </div>

    {{-- FILTER (INI DIPISAH - TIDAK NESTED FORM) --}}
    <div class="card mb-3 no-print">
        <div class="card-body">

            <form method="GET" action="{{ route('qurban.kupon.index') }}">
                <div class="row">

                    <div class="col-md-4 mb-2">
                        <input type="text" name="nama" class="form-control"
                            placeholder="Cari Nama..."
                            value="{{ request('nama') }}">
                    </div>

                    <div class="col-md-2 mb-2">
                        <input type="number" name="rw" class="form-control"
                            placeholder="RW"
                            value="{{ request('rw') }}">
                    </div>

                    <div class="col-md-2 mb-2">
                        <input type="number" name="rt" class="form-control"
                            placeholder="RT"
                            value="{{ request('rt') }}">
                    </div>

                    <div class="col-md-2 mb-2">
                        <button type="submit" class="btn btn-primary btn-block">
                            Cari
                        </button>
                    </div>

                    <div class="col-md-2 mb-2">
                        <a href="{{ route('qurban.kupon.index') }}" class="btn btn-secondary btn-block">
                            Reset
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>

    {{-- FORM PRINT SELECTED --}}
    <form method="POST" action="{{ route('qurban.print.selected') }}" id="formPrintSelected">
        @csrf

        {{-- CHECK ALL --}}
        <div class="mb-3 no-print">
            <label>
                <input type="checkbox" id="checkAll">
                Pilih Semua
            </label>
        </div>

        {{-- AREA CETAK --}}
        <div class="print-area">

            <div class="kupon-grid">

                @foreach ($qurban as $item)
                    @foreach ($item->kuponqurban as $kupon)

                        <div class="kupon-wrapper">

                            {{-- CHECKBOX --}}
                            <div class="no-print mb-1">
                                <label>
                                    <input type="checkbox"
                                        class="item-checkbox"
                                        name="selected_ids[]"
                                        value="{{ $item->id }}">

                                    {{ $item->nama }}
                                    (RT {{ $item->rt }} / RW {{ $item->rw }})
                                </label>
                            </div>

                            <div class="kupon-card">

                                {{-- KOP --}}
                                <div class="kop">
                                    <div class="masjid">
                                        TAKMIR MASJID ABU BAKAR AS-SHIDDIQI
                                    </div>

                                    <div class="alamat">
                                        Jl. Kaca Piring Lingkungan Gebang Tengah <br>
                                        Kel. Gebang Kec. Patrang Kab. Jember
                                    </div>
                                </div>

                                {{-- JUDUL --}}
                                <div class="judul-kupon">
                                    KUPON PENGAMBILAN DAGING QURBAN
                                </div>

                                {{-- CONTENT --}}
                                <div class="content-kupon">

                                    <div class="isi-kupon">
                                        <table width="100%">
                                            <tr>
                                                <td width="70"><b>Nama</b></td>
                                                <td>: {{ $item->nama }}</td>
                                            </tr>

                                            <tr>
                                                <td><b>Alamat</b></td>
                                                <td>: {{ $item->alamat }}</td>
                                            </tr>

                                            <tr>
                                                <td><b>RT / RW</b></td>
                                                <td>: {{ $item->rt }} / {{ $item->rw }}</td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" class="note-kupon">
                                                    <b>Note:</b> Kupon harap dibawa saat pengambilan daging qurban
                                                </td>
                                            </tr>
                                        </table>
                                    </div>

                                    <div class="qr-area">
                                        {!! QrCode::size(90)->generate($kupon->qr_code) !!}
                                        <div class="kode-kupon">
                                            {{ $kupon->qr_code }}
                                        </div>
                                    </div>

                                </div>

                            </div>

                        </div>

                    @endforeach
                @endforeach

            </div>

        </div>

    </form>

</div>
@endsection

@section('script')
<script>
document.getElementById('checkAll').addEventListener('change', function () {
    document.querySelectorAll('.item-checkbox')
        .forEach(cb => cb.checked = this.checked);
});
</script>

<style>
@page { size: A4; margin: 5mm; }

body {
    margin: 0;
    padding: 0;
    background: #fff;
    font-family: Arial, sans-serif;
}

.kupon-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 10px;
}

.kupon-wrapper {
    break-inside: avoid;
    page-break-inside: avoid;
}

.kupon-card {
    border: 1px dashed #000;
    padding: 10px;
    height: 220px;
    display: flex;
    flex-direction: column;
}

.kop {
    text-align: center;
    border-bottom: 1px solid #000;
    margin-bottom: 6px;
}

.masjid { font-weight: bold; font-size: 13px; }

.alamat { font-size: 9px; }

.judul-kupon {
    text-align: center;
    font-weight: bold;
    font-size: 12px;
    margin-bottom: 8px;
}

.content-kupon {
    display: flex;
    justify-content: space-between;
    flex: 1;
}

.isi-kupon { width: 68%; font-size: 13px; }

.isi-kupon table td { padding-bottom: 3px; }

.qr-area {
    width: 32%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.kode-kupon {
    font-size: 9px;
    text-align: center;
    margin-top: 4px;
}

.note-kupon {
    font-size: 8px;
    text-align: center;
    font-style: italic;
}

@media print {
    .no-print { display: none !important; }
    body { background: #fff; }
}
@media print {
    footer,
    .main-footer {
        display: none !important;
    }
}
</style>
@endsection