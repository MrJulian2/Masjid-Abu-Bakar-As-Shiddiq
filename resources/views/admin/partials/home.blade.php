@extends('admin.index')

@section('content')

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Home</h1>
            </div>
        </div>
    </div>
</section>

<!-- Main Content -->
<section class="content">

    @if (Auth::user()->role == 'admin' || Auth::user()->role == 'bendahara')

        <div class="row">

            <!-- SALDO KAS MASJID -->
            <div class="col-md-4">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Saldo Kas Masjid</h3>
                    </div>

                    <div class="card-body">
                        <h2>
                            @currency($saldo->saldo + $home->sum('pemasukan') - $home->sum('pengeluaran'))
                        </h2>
                    </div>
                </div>
            </div>

            <!-- SALDO KAS SOSIAL -->
            <div class="col-md-4">
                <div class="card card-success">
                    <div class="card-header">
                        <h3 class="card-title">Saldo Kas Sosial</h3>
                    </div>

                    <div class="card-body">
                        <h4 class="text-muted text-center">Belum ada data</h4>
                    </div>
                </div>
            </div>

            <!-- PENGGUNA SISTEM -->
            <div class="col-md-4">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Pengguna Sistem</h3>
                    </div>

                    <div class="card-body text-center">
                        <h2>{{ $user->count() }}</h2>
                    </div>
                </div>
            </div>

        </div> {{-- END ROW --}}

    @else

        <!-- Jika bukan admin/bendahara -->
        <div class="row">

            <div class="col-md-4">
                <div class="card card-warning">
                    <div class="card-header">
                        <h3 class="card-title">Pengguna Sistem</h3>
                    </div>

                    <div class="card-body text-center">
                        <h2>{{ $user->count() }}</h2>
                    </div>
                </div>
            </div>

        </div>

    @endif

</section>

@endsection