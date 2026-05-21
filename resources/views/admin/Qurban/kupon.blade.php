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

            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Print Kupon
            </button>

            <a href="{{ route('qurban.index') }}" class="btn btn-secondary">
                Kembali
            </a>

        </div>

    </div>

    {{-- PRINT AREA --}}
    <div class="print-area">

        @foreach ($qurban->chunk(10) as $chunk)
        <div class="page">

            <div class="kupon-grid">

                @foreach ($chunk as $item)
                    @foreach ($item->kuponqurban as $kupon)

                    <div class="kupon-card">

                        {{-- KOP --}}
                        <div class="kop">
                            <div class="masjid">
                                TAKMIR MASJID ABU BAKAR AS-SHIDDIQI
                            </div>

                            <div class="alamat">
                                Jl. Kaca Piring Lingkungan Gebang Tengah <br>
                                Kelurahan Gebang Kecamatan Patrang Kabupaten Jember
                            </div>
                        </div>

                        {{-- JUDUL --}}
                        <div class="judul-kupon">
                            KUPON PENGAMBILAN DAGING QURBAN
                        </div>

                        {{-- CONTENT --}}
                        <div class="content-kupon">

                            {{-- DATA --}}
                            <div class="isi-kupon">

                                <table width="100%">
                                    <tr>
                                        <td><b>Nama</b></td>
                                        <td>: {{ $item->nama }}</td>
                                    </tr>

                                    <tr>
                                        <td><b>No HP</b></td>
                                        <td>: {{ $item->nomor_hp }}</td>
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

                            {{-- QR --}}
                            <div class="qr-area">

                                {!! QrCode::size(85)->generate($kupon->qr_code) !!}

                                <div class="kode-kupon">
                                    {{ $kupon->qr_code }}
                                </div>

                            </div>

                        </div>

                    </div>

                    @endforeach
                @endforeach

            </div>

        </div>
        @endforeach

    </div>

</div>
@endsection


@section('script')
<style>

/* =========================
   GLOBAL FIX
========================= */
* {
    box-sizing: border-box;
}

/* =========================
   PAGE FIX (ANTI ACak)
========================= */
.page {
    width: 210mm;
    height: 297mm;
    padding: 5mm;
    overflow: hidden;
    page-break-after: always;
}

/* =========================
   BODY
========================= */
body {
    margin: 0;
    padding: 0;
    font-family: Arial, sans-serif;
    background: #fff;
}

/* =========================
   GRID (FLEX STABIL)
========================= */
.kupon-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

/* =========================
   CARD (FIX HEIGHT = SAMA SEMUA)
========================= */
.kupon-card {
    width: calc(50% - 6px);

    height: 230px;   /* 🔥 FIX TINGGI WAJIB */
    overflow: hidden;

    border: 1px dashed #000;
    background: #fff;

    padding: 8px;

    display: flex;
    flex-direction: column;

    page-break-inside: avoid;
    break-inside: avoid;
}

/* =========================
   KOP
========================= */
.kop {
    text-align: center;
    border-bottom: 1px solid #000;
    padding-bottom: 4px;
    margin-bottom: 4px;
}

.masjid {
    font-weight: bold;
    font-size: 12px;
}

.alamat {
    font-size: 9px;
    line-height: 1.2;
}

/* =========================
   JUDUL
========================= */
.judul-kupon {
    text-align: center;
    font-weight: bold;
    font-size: 11px;
    margin-bottom: 5px;
}

/* =========================
   CONTENT
========================= */
.content-kupon {
    display: flex;
    justify-content: space-between;
    gap: 6px;
    flex: 1;
}

/* =========================
   DATA (FIX HEIGHT AREA)
========================= */
.isi-kupon {
    width: 68%;
    font-size: 11px;
    line-height: 1.2;

    height: 120px; /* 🔥 FIX AREA TEKS */
    overflow: hidden;
}

.isi-kupon table td {
    padding-bottom: 2px;
    vertical-align: top;
}

.isi-kupon table td:first-child {
    width: 65px;
    font-weight: 600;
}

/* =========================
   QR AREA (FIX)
========================= */
.qr-area {
    width: 32%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;

    height: 120px; /* FIX */
}

.qr-area svg {
    width: 85px !important;
    height: 85px !important;
}

.kode-kupon {
    font-size: 8px;
    margin-top: 3px;
    text-align: center;
    word-break: break-word;
    font-weight: bold;
}

/* =========================
   NOTE
========================= */
.note-kupon {
    font-size: 8px;
    font-style: italic;
    text-align: center;
    padding-top: 4px;
}

/* =========================
   PRINT MODE
========================= */
@media print {

    .no-print {
        display: none !important;
    }

    body {
        margin: 0;
        padding: 0;
    }

    .page {
        width: 210mm;
        height: 297mm;
        overflow: hidden;
        page-break-after: always;
    }

    .kupon-card {
        height: 230px !important;
        overflow: hidden !important;
    }

    .main-header,
    .main-sidebar,
    .navbar,
    .main-footer,
    footer,
    .brand-link {
        display: none !important;
    }
}

/* =========================
   MOBILE
========================= */
@media screen and (max-width: 768px) {

    .kupon-grid {
        flex-direction: column;
    }

    .kupon-card {
        width: 100%;
        height: auto;
    }

    .qr-area svg {
        width: 75px !important;
        height: 75px !important;
    }
}

</style>
@endsection