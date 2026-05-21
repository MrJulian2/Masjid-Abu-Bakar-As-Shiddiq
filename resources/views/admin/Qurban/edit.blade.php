@extends('admin.index')

@section('content')
<section class="content">
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <h3>Edit Data Penerima Qurban</h3>
            </div>

            <div class="card-body">

                <form action="{{ route('qurban.update', $qurban->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" name="nama" class="form-control" value="{{ $qurban->nama }}">
                    </div>

                    <div class="form-group">
                        <label>No HP</label>
                        <input type="text" name="nomor_hp" class="form-control" value="{{ $qurban->nomor_hp }}">
                    </div>

                    <div class="form-group">
                        <label>Alamat</label>
                        <textarea name="alamat" class="form-control">{{ $qurban->alamat }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>RT</label>
                        <input type="text" name="rt" class="form-control" value="{{ $qurban->rt }}">
                    </div>

                    <div class="form-group">
                        <label>RW</label>
                        <input type="text" name="rw" class="form-control" value="{{ $qurban->rw }}">
                    </div>

                    <div class="form-group">
                        <label>Jumlah Kupon</label>
                        <input type="number" name="jumlah_kupon" class="form-control"
                            value="{{ count($kupon) }}" min="1">
                    </div>

                    <hr>

                    <button type="submit" class="btn btn-primary mb-3">
                        Update
                    </button>

                </form>

                <h5>Daftar QR Code</h5>

                <table id="qr-table" class="table table-bordered table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode QR</th>
                            <th>QR Code</th>
                            <th>Status</th>
                            <th>DiScan Oleh</th>
                            <th>DiScan Pada</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($kupon as $key => $k)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $k->qr_code }}</td>

                                <td>
                                    {!! QrCode::size(70)->generate($k->qr_code) !!}
                                </td>

                                <td>
                                    @if ($k->status == 'belum_diambil')
                                        <span class="badge badge-danger">Belum Diambil</span>
                                    @else
                                        <span class="badge badge-success">Sudah Diambil</span>
                                    @endif
                                </td>

                                <td>{{ $k->scannedBy->name ?? '-' }}</td>

                                <td>
                                    {{ $k->scanned_at
                                        ? \Carbon\Carbon::parse($k->scanned_at)->format('d M Y H:i')
                                        : '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>
</section>
@endsection


@section('script')

<script>
$(function () {

    $('#qr-table').DataTable({
        paging: true,
        lengthChange: true,
        searching: true,
        ordering: true,
        info: true,

        autoWidth: false,
        responsive: true
    });

});
</script>

@endsection