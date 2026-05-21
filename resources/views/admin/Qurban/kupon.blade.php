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

        <div class="kupon-grid">

            @foreach ($qurban as $item)
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

                            {!! QrCode::size(90)->generate($kupon->qr_code) !!}

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
   A4 SETTING
========================= */
@page {
    size: A4;
    margin: 5mm;
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
   GRID
========================= */
.kupon-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 6px;
}

/* =========================
   CARD
========================= */
.kupon-card {
    border: 1px dashed #000;
    background: #fff;

    padding: 8px;

    min-height: 220px;
    height: auto;

    overflow: hidden;

    display: flex;
    flex-direction: column;
}

/* =========================
   KOP
========================= */
.kop {
    text-align: center;
    border-bottom: 1px solid #000;
    padding-bottom: 5px;
    margin-bottom: 5px;
}

.masjid {
    font-weight: bold;
    font-size: 13px;
}

.alamat {
    font-size: 9px;
}

/* =========================
   JUDUL
========================= */
.judul-kupon {
    text-align: center;
    font-weight: bold;
    font-size: 12px;
    margin-bottom: 6px;
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
   DATA
========================= */
.isi-kupon {
    width: 68%;
    font-size: 12px;
    line-height: 1.3;
}

.isi-kupon table td {
    padding-bottom: 2px;
    vertical-align: top;
}

.isi-kupon table td:first-child {
    width: 70px;
    font-weight: 600;
}

/* =========================
   QR
========================= */
.qr-area {
    width: 32%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.qr-area svg {
    width: 100px !important;
    height: 100px !important;
}

.kode-kupon {
    font-size: 8.5px;
    margin-top: 4px;
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
    padding-top: 5px;
}

/* =========================
   PRINT FIX
========================= */
@media print {

    .no-print {
        display: none !important;
    }

    body {
        margin: 0;
        padding: 0;
    }

    .kupon-grid {
        gap: 5px;
    }

    .isi-kupon {
        font-size: 11px;
        line-height: 1.25;
    }

    .judul-kupon {
        font-size: 11px;
    }

    .masjid {
        font-size: 12px;
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
        grid-template-columns: 1fr;
    }

    .kupon-card {
        min-height: auto;
    }

    .qr-area svg {
        width: 80px !important;
        height: 80px !important;
    }
}

</style>
@endsection