<!DOCTYPE html>
<html>
<head>
    <title>Laporan Qurban</title>

    <style>
        body{
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin:0;
        }

        /* =========================
            KOP SURAT
        ========================= */
        .kop{
            text-align:center;
            margin-bottom:10px;
            border-bottom:2px solid #000;
            padding-bottom:10px;
        }

        .kop .title{
            font-size:16px;
            font-weight:bold;
            text-transform:uppercase;
        }

        .kop .sub{
            font-size:12px;
        }

        .kop .alamat{
            font-size:11px;
        }

        /* =========================
            SECTION TITLE
        ========================= */
        .section-title{
            margin:10px 0 5px 0;
            font-weight:bold;
            font-size:13px;
            background:#f2f2f2;
            padding:5px;
        }

        /* =========================
            TABLE
        ========================= */
        table{
            width:100%;
            border-collapse: collapse;
            margin-bottom:10px;
        }

        table, th, td{
            border:1px solid #000;
        }

        th, td{
            padding:5px;
            font-size:11px;
        }

        th{
            background:#f2f2f2;
        }

        .highlight{
            font-weight:bold;
            background:#e8f4ff;
        }

        .page-break {
    page-break-before: always;
    break-before: page;
}
    </style>
</head>

<body>

{{-- =========================
    KOP SURAT
========================= --}}
<div class="kop">
    <div class="title">TAKMIR MASJID ABU BAKAR AS-SHIDDIQ</div>
    <div class="sub">Jl. Kaca Piring Lingkungan Gebang Tengah</div>
    <div class="alamat">Kelurahan Gebang - Kecamatan Patrang - Kabupaten Jember</div>
</div>

{{-- =========================
    DETAIL DATA
========================= --}}
<div class="section-title">DATA PENERIMA QURBAN</div>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>No HP</th>
            <th>Alamat</th>
            <th>RT/RW</th>
            <th>Total Kupon</th>
            <th>Sudah Diambil</th>
            <th>Belum Diambil</th>
        </tr>
    </thead>

    <tbody>

        @php
            $grandTotal = 0;
            $grandSudah = 0;
            $grandBelum = 0;
        @endphp

        @foreach ($qurban as $key => $row)

            @php
                $total = $row->kuponqurban->count();
                $sudah = $row->kuponqurban->where('status','sudah_diambil')->count();
                $belum = $row->kuponqurban->where('status','belum_diambil')->count();

                $grandTotal += $total;
                $grandSudah += $sudah;
                $grandBelum += $belum;
            @endphp

            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $row->nama }}</td>
                <td>{{ $row->nomor_hp }}</td>
                <td>{{ $row->alamat }}</td>
                <td>{{ $row->rt }} / {{ $row->rw }}</td>
                <td>{{ $total }}</td>
                <td>{{ $sudah }}</td>
                <td>{{ $belum }}</td>
            </tr>

        @endforeach

    </tbody>
</table>
<div class="page-break"></div>
{{-- =========================
    REKAP RW / RT
========================= --}}
<div class="section-title">REKAP PER RW / RT</div>

@foreach ($rwStats as $rw => $data)

<table>

    <tr class="highlight">
        <td colspan="4">RW {{ $rw }}</td>
    </tr>

    <tr>
        <th>RT</th>
        <th>Total Kupon</th>
        <th>Sudah Diambil</th>
        <th>Belum Diambil</th>
    </tr>

    @php
        $rwTotal = 0;
        $rwSudah = 0;
        $rwBelum = 0;
    @endphp

    @foreach ($data as $item)

        @php
            $rwTotal += $item->total;
            $rwSudah += $item->sudah;
            $rwBelum += $item->belum;
        @endphp

        <tr>
            <td>RT {{ $item->rt }}</td>
            <td>{{ $item->total }}</td>
            <td>{{ $item->sudah }}</td>
            <td>{{ $item->belum }}</td>
        </tr>

    @endforeach

    <tr class="highlight">
        <td>Total Kupon RW {{ $rw }}</td>
        <td>{{ $rwTotal }}</td>
        <td>{{ $rwSudah }}</td>
        <td>{{ $rwBelum }}</td>
    </tr>

</table>

@endforeach
{{-- <div class="page-break"></div> --}}
{{-- =========================
    GRAND TOTAL
========================= --}}
<div class="section-title">GRAND TOTAL</div>

<table>
    <tr class="highlight">
        <td>Total Kupon</td>
        <td>{{ $grandTotal }}</td>
    </tr>

    <tr class="highlight">
        <td>Sudah Diambil</td>
        <td>{{ $grandSudah }}</td>
    </tr>

    <tr class="highlight">
        <td>Belum Diambil</td>
        <td>{{ $grandBelum }}</td>
    </tr>
</table>

</body>
</html>