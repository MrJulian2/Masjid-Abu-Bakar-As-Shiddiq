@extends('admin.index')

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3 no-print">

            {{-- LEFT --}}
            <h3 class="mb-0">Cetak Kupon Qurban</h3>

            {{-- RIGHT BUTTON GROUP --}}
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

        {{-- AREA CETAK (SEMUA KUPON 1 GRID SAJA) --}}
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
                                            <td width="70"><b>Nama</b></td>
                                            <td>: {{ $item->nama }}</td>
                                        </tr>

                                        {{-- <tr>
                                            <td><b>No HP</b></td>
                                            <td>: {{ $item->nomor_hp }}</td>
                                        </tr> --}}

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
                A4 SETTING
            ========================= */
        @page {
            size: A4;
            margin: 5mm;
        }

        /* =========================
                GLOBAL RESET
            ========================= */
        body {
            margin: 0;
            padding: 0;
            background: #fff;
            font-family: Arial, sans-serif;
        }

        /* =========================
                GRID 10 KUPON / A4
            ========================= */
        .kupon-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin: 0;
            padding: 0;
        }

        /* =========================
                CARD KUPON (STABIL)
            ========================= */
        .kupon-card {
            border: 1px dashed #000;
            background: #fff;

            padding: 10px;

            height: 220px;
            overflow: visible;

            break-inside: avoid;
            page-break-inside: avoid;

            display: flex;
            flex-direction: column;
        }

        /* =========================
                KOP
            ========================= */
        .kop {
            text-align: center;
            border-bottom: 1px solid #000;
            padding-bottom: 6px;
            margin-bottom: 6px;
        }

        .masjid {
            font-weight: bold;
            font-size: 13px;
            line-height: 1.2;
        }

        .alamat {
            font-size: 9px;
            line-height: 1.3;
        }

        /* =========================
                JUDUL
            ========================= */
        .judul-kupon {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 8px;
        }

        /* =========================
                CONTENT
            ========================= */
        .content-kupon {
            display: flex;
            justify-content: space-between;
            gap: 8px;

            flex: 1;
        }

        /* =========================
                DATA (TEKS LEBIH BESAR)
            ========================= */
        .isi-kupon {
            width: 68%;

            font-size: 13.5px;
            /* 🔥 FIX: lebih besar */
            line-height: 1.5;
        }

        .isi-kupon table td {
            padding-bottom: 3px;
            vertical-align: top;
        }

        /* label kiri (Nama, HP, dll) */
        .isi-kupon table td:first-child {
            width: 75px;
            font-weight: 600;
        }

        /* =========================
                QR AREA (FIX TOTAL CENTER)
            ========================= */
        .qr-area {
            width: 32%;

            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;

            min-height: 120px;
        }

        /* =========================
                QR CODE (AMAN TIDAK HILANG)
            ========================= */
        .qr-area svg {
            width: 110px !important;
            height: 110px !important;

            max-width: 100%;
            max-height: 100%;

            display: block;
            margin: 0 auto;
        }

        /* =========================
                KODE QR
            ========================= */
        .kode-kupon {
            font-size: 9px;
            margin-top: 4px;
            line-height: 1.2;
            text-align: center;
            word-break: break-all;
            font-weight: bold;
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
                background: #fff;
            }

            .container-fluid {
                padding: 0 !important;
                margin: 0 !important;
            }

            /* hide admin layout */
            .main-header,
            .main-sidebar,
            .navbar,
            .main-footer,
            footer,
            .brand-link {
                display: none !important;
            }
        }

        .note-kupon {
            padding-top: 6px;
            font-size: 8.5px;
            font-style: italic;
            text-align: center;
            color: #000;
            line-height: 1.2;
        }

        /* =========================
       MOBILE RESPONSIVE ONLY
       (TIDAK MENGGANGGU PRINT)
    ========================= */
        @media screen and (max-width: 768px) {

            /* GRID jadi 1 kolom */
            .kupon-grid {
                grid-template-columns: 1fr !important;
                gap: 12px !important;
                padding: 10px;
            }

            /* CARD auto tinggi */
            .kupon-card {
                height: auto !important;
                min-height: unset !important;
            }

            /* teks lebih kecil biar muat */
            .isi-kupon {
                font-size: 12px !important;
                width: 65% !important;
            }

            /* QR lebih kecil */
            .qr-area svg {
                width: 80px !important;
                height: 80px !important;
            }

            /* HEADER BUTTON STACK */
            .no-print .d-flex {
                flex-direction: column !important;
                width: 100%;
            }

            .no-print .btn {
                width: 100% !important;
            }

            .no-print h3 {
                text-align: center;
                width: 100%;
            }
        }
    </style>
@endsection
