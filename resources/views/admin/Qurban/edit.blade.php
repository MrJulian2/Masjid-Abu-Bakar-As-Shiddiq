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
                            <input type="number" name="jumlah_kupon" class="form-control" value="{{ count($kupon) }}"
                                min="1">
                        </div>

                        <hr>

                        <h5>Daftar QR Code</h5>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode QR</th>
                                    <th>QR Code</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($kupon as $key => $k)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $k->qr_code }}</td>
                                        <td>{!! QrCode::size(150)->generate($k->qr_code) !!}</td>
                                        <td>
                                            @if ($k->status == 'belum_diambil')
                                                <span class="badge badge-danger">Belum Diambil</span>
                                            @elseif ($k->status == 'sudah_diambil')
                                                <span class="badge badge-success">Sudah Diambil</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <button class="btn btn-primary" type="submit">
                            Update
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </section>
@endsection
