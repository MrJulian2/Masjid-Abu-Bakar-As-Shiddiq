@extends('admin.index')

@section('content')
    <section class="content">
        <div class="container-fluid">

            {{-- Informasi Muzakki --}}
            <div class="card card-success card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user mr-1"></i>
                        Detail Muzakki
                    </h3>

                    <div class="card-tools">
                        <a href="{{ route('zakat-muzakki.kelola') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Nama Muzakki</strong>
                            <p>{{ $muzakki->nama }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>Kategori</strong>
                            <p>Warga {{ ucfirst($muzakki->kategori ?? '-') }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>Alamat</strong>
                            <p>{{ $muzakki->alamat ?? '-' }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>RT</strong>
                            <p>{{ $muzakki->rt ?? '-' }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>RW</strong>
                            <p>{{ $muzakki->rw ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Daftar Anggota Muzakki --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-users mr-1"></i>
                        Daftar Anggota Muzakki
                    </h3>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th>Nama</th>
                                    <th>Berat Beras</th>
                                    <th>Nominal Uang</th>
                                    <th>Jenis Zakat</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($muzakki->details as $detail)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $detail->nama ?? '-' }}</td>
                                        <td>
                                            {{ $detail->berat_beras !== null ? number_format($detail->berat_beras, 2, ',', '.') . ' kg' : '-' }}
                                        </td>
                                        <td>
                                            {{ $detail->nominal_uang !== null ? 'Rp ' . number_format($detail->nominal_uang, 0, ',', '.') : '-' }}
                                        </td>
                                        <td>{{ ucfirst($detail->jenis_zakat ?? '-') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            Belum ada detail anggota Muzakki.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    <strong>Total Anggota:</strong>
                    {{ $muzakki->details->count() }} orang
                </div>
            </div>


        </div>
    </section>
@endsection
