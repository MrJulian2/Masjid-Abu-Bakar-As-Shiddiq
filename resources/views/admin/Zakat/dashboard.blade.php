@extends('admin.index')

@section('content')
<section class="content">
    <div class="container-fluid">

        {{-- Header --}}
        <div class="row mb-3">
            <div class="col-12">
                <h3>Dashboard Monitoring Zakat Fitrah</h3>
            </div>
        </div>

        {{-- Statistik Utama --}}
        <div class="row">

            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($totalMuzakki) }}</h3>
                        <p>Total Muzakki</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($totalJiwa) }}</h3>
                        <p>Total Jiwa</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-friends"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($totalBeras, 2) }} Kg</h3>
                        <p>Zakat Beras</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-seedling"></i>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>Rp {{ number_format($totalUang, 0, ',', '.') }}</h3>
                        <p>Zakat Uang</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- Statistik Mustahik --}}
        <div class="row">

            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-primary">
                        <i class="fas fa-hand-holding-heart"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">Total Mustahik</span>
                        <span class="info-box-number">
                            {{ number_format($totalMustahik) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">Sudah Validasi</span>
                        <span class="info-box-number">
                            {{ number_format($sudahValidasi) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="info-box">
                    <span class="info-box-icon bg-danger">
                        <i class="fas fa-times-circle"></i>
                    </span>

                    <div class="info-box-content">
                        <span class="info-box-text">Belum Validasi</span>
                        <span class="info-box-number">
                            {{ number_format($belumValidasi) }}
                        </span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Tabel Ringkasan --}}
        <div class="row">

            <div class="col-md-6">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Ringkasan Pengumpulan
                        </h3>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-bordered">

                            <tr>
                                <th>Total Muzakki</th>
                                <td>{{ number_format($totalMuzakki) }}</td>
                            </tr>

                            <tr>
                                <th>Total Jiwa</th>
                                <td>{{ number_format($totalJiwa) }}</td>
                            </tr>

                            <tr>
                                <th>Total Beras</th>
                                <td>{{ number_format($totalBeras, 2) }} Kg</td>
                            </tr>

                            <tr>
                                <th>Total Uang</th>
                                <td>Rp {{ number_format($totalUang, 0, ',', '.') }}</td>
                            </tr>

                        </table>
                    </div>

                </div>

            </div>

            <div class="col-md-6">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            Ringkasan Distribusi
                        </h3>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-bordered">

                            <tr>
                                <th>Total Mustahik</th>
                                <td>{{ number_format($totalMustahik) }}</td>
                            </tr>

                            <tr>
                                <th>Sudah Validasi</th>
                                <td>{{ number_format($sudahValidasi) }}</td>
                            </tr>

                            <tr>
                                <th>Belum Validasi</th>
                                <td>{{ number_format($belumValidasi) }}</td>
                            </tr>

                            <tr>
                                <th>Progress</th>
                                <td>
                                    {{ $totalMustahik > 0
                                        ? round(($sudahValidasi / $totalMustahik) * 100, 2)
                                        : 0 }} %
                                </td>
                            </tr>

                        </table>
                    </div>

                </div>

            </div>

        </div>

    </div>
</section>
@endsection