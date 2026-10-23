
@extends('admin.index')

@section('content')
<div class="container-fluid">

    {{-- HEADER --}}
    <div class="d-flex justify-content-between align-items-center mb-3 no-print flex-wrap">
        <div class="mb-2">
            <h3 class="mb-0">Cetak Kupon Zakat Fitrah</h3>

            @if ($periodeAktif)
                <p class="text-muted mb-0">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    Periode: {{ $periodeAktif->nama }}
                </p>
            @endif
        </div>

        <div class="d-flex flex-wrap" style="gap: 8px;">
            <button type="button"
                onclick="window.print()"
                class="btn btn-primary"
                {{ !$periodeAktif || $kupons->isEmpty() ? 'disabled' : '' }}>
                <i class="fas fa-print"></i> Print Semua
            </button>

            <button type="submit"
                form="formPrintSelected"
                class="btn btn-success"
                {{ !$periodeAktif || $kupons->isEmpty() ? 'disabled' : '' }}>
                <i class="fas fa-check"></i> Cetak Terpilih
            </button>

            <a href="{{ route('zakat-mustahik.halamanCetakKupon') }}"
                class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- ALERT PERIODE --}}
    @if (!$periodeAktif)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <h5>
                <i class="fas fa-exclamation-triangle mr-1"></i>
                Belum Ada Periode Zakat Aktif!
            </h5>

            <p class="mb-2">
                Data kupon tidak dapat ditampilkan atau dicetak
                sebelum periode zakat diaktifkan.
            </p>

            <strong>Silakan aktifkan periode Zakat Fitrah terlebih dahulu.</strong>

            <button type="button" class="close" data-dismiss="alert"
                aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @else

        {{-- FILTER --}}
        <div class="card mb-3 no-print">
            <div class="card-body">
                <form method="GET" action="{{ route('zakat-mustahik.halamanCetakKupon') }}">
                    <div class="row">

                        <div class="col-md-4 mb-2">
                            <input type="text"
                                name="nama"
                                class="form-control"
                                placeholder="Cari Nama Mustahik..."
                                value="{{ request('nama') }}">
                        </div>

                        <div class="col-md-2 mb-2">
                            <input type="number"
                                name="rw"
                                class="form-control"
                                placeholder="RW"
                                value="{{ request('rw') }}">
                        </div>

                        <div class="col-md-2 mb-2">
                            <input type="number"
                                name="rt"
                                class="form-control"
                                placeholder="RT"
                                value="{{ request('rt') }}">
                        </div>

                        <div class="col-md-2 mb-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>

                        <div class="col-md-2 mb-2">
                            <a href="{{ route('zakat-mustahik.halamanCetakKupon') }}"
                                class="btn btn-secondary btn-block">
                                <i class="fas fa-sync-alt"></i> Reset
                            </a>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        {{-- PESAN KOSONG --}}
        @if ($kupons->isEmpty())
            <div class="alert alert-info no-print">
                <i class="fas fa-info-circle mr-1"></i>
                Tidak ada kupon mustahik pada periode
                <strong>{{ $periodeAktif->nama }}</strong>
                dengan filter tersebut.
            </div>
        @else

            {{-- FORM CETAK TERPILIH --}}
            <form method="POST"
                action="{{ route('zakat-mustahik.printSelected') }}"
                id="formPrintSelected">
                @csrf

                {{-- PILIH SEMUA --}}
                <div class="mb-3 no-print">
                    <label>
                        <input type="checkbox" id="checkAll">
                        Pilih Semua
                    </label>
                    <span class="text-muted ml-2">
                        ({{ $kupons->count() }} kupon)
                    </span>
                </div>

                {{-- AREA CETAK --}}
                <div class="print-area">
                    <div class="kupon-grid">

                        @foreach ($kupons as $kupon)
                            @php
                                $mustahik = $kupon->mustahik;
                            @endphp

                            @if ($mustahik)
                                <div class="kupon-wrapper">

                                    {{-- CHECKBOX --}}
                                    <div class="no-print mb-1">
                                        <label>
                                            <input type="checkbox"
                                                class="item-checkbox"
                                                name="selected_ids[]"
                                                value="{{ $kupon->id }}">

                                            {{ $mustahik->nama }}
                                            (RT {{ $mustahik->rt ?? '-' }}
                                            / RW {{ $mustahik->rw ?? '-' }})
                                        </label>
                                    </div>

                                    {{-- KARTU KUPON --}}
                                    <div class="kupon-card">

                                        {{-- KOP MASJID --}}
                                        <div class="kop">
                                            <div class="masjid">
                                                TAKMIR MASJID ABU BAKAR AS-SHIDDIQI
                                            </div>

                                            <div class="alamat">
                                                Jl. Kaca Piring Lingkungan Gebang Tengah<br>
                                                Kel. Gebang Kec. Patrang Kab. Jember
                                            </div>
                                        </div>

                                        {{-- JUDUL --}}
                                        <div class="judul-kupon">
                                            KUPON PENERIMAAN ZAKAT FITRAH -
                                            {{ $periodeAktif->nama }}
                                        </div>

                                        {{-- ISI KUPON --}}
                                        <div class="content-kupon">

                                            <div class="isi-kupon">
                                                <table width="100%">
                                                    <tr>
                                                        <td width="65"><b>Nama</b></td>
                                                        <td>: {{ $mustahik->nama }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td><b>Alamat</b></td>
                                                        <td>: {{ $mustahik->alamat }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td><b>RT / RW</b></td>
                                                        <td>: {{ $mustahik->rt ?? '-' }}
                                                            / {{ $mustahik->rw ?? '-' }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td colspan="2" class="note-kupon">
                                                            <b>Catatan:</b>
                                                            Kupon harap dibawa saat
                                                            penerimaan zakat fitrah.
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>

                                            {{-- QR CODE --}}
                                            <div class="qr-area">
                                                {!! QrCode::size(90)->generate($kupon->qr_code) !!}

                                                <div class="kode-kupon">
                                                    {{ $kupon->qr_code }}
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach

                    </div>
                </div>
            </form>
        @endif
    @endif

</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkAll = document.getElementById('checkAll');

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            document.querySelectorAll('.item-checkbox').forEach(function (checkbox) {
                checkbox.checked = checkAll.checked;
            });
        });
    }

    @if ($autoPrint)
        window.addEventListener('load', function () {
            window.print();
        });
    @endif
});
</script>

<style>
@page {
    size: 210mm 330mm;
    margin: 5mm;
}

.kupon-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 1fr));
    gap: 10px;
}

.kupon-wrapper {
    min-width: 0;
    break-inside: avoid;
    page-break-inside: avoid;
}

.kupon-card {
    border: 1px dashed #000;
    padding: 10px;
    height: 220px;
    display: flex;
    flex-direction: column;
    color: #000;
    background: #fff;
    box-sizing: border-box;
}

.kop {
    text-align: center;
    border-bottom: 1px solid #000;
    margin-bottom: 6px;
    padding-bottom: 4px;
}

.masjid {
    font-weight: bold;
    font-size: 13px;
}

.alamat {
    font-size: 9px;
}

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
    min-height: 0;
}

.isi-kupon {
    width: 68%;
    font-size: 13px;
    overflow-wrap: anywhere;
}

.isi-kupon table td {
    padding-bottom: 3px;
    vertical-align: top;
}

.qr-area {
    width: 32%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
}

.qr-area svg {
    max-width: 100%;
    height: auto;
}

.kode-kupon {
    font-size: 9px;
    text-align: center;
    margin-top: 4px;
    overflow-wrap: anywhere;
}

.note-kupon {
    font-size: 8px;
    text-align: center;
    font-style: italic;
}

@media print {
    html,
    body {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }

    .no-print,
    footer,
    .main-footer,
    .main-header,
    .main-sidebar,
    .control-sidebar {
        display: none !important;
    }

    .content-wrapper,
    .content,
    .container-fluid,
    .print-area {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        min-height: 0 !important;
        box-shadow: none !important;
        background: #fff !important;
    }

    .kupon-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 5mm;
    }

    .kupon-wrapper {
        break-inside: avoid !important;
        page-break-inside: avoid !important;
    }

    .kupon-card {
        height: 220px;
        border: 1px dashed #000 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>
@endsection
