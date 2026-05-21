@extends('admin.index')

@section('head')
   

@endsection

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit Admin Users</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Edit Admin Users</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Edit Admin Users</h3>
                        </div>
                        <div class="card-body">
                            <!-- Date -->
                            <div class="col-6">
                                <form action="{{ route('users.update', $edit->id) }}" method="POST" >
                                   @csrf
                                   @method('PUT')
                                    <div class="form-group">
                                        <label for="nama">Nama</label>
                                        <input type="text" class="form-control @error('name')
                                                    is-invalid
                                            @enderror" id="nama" value="{{ $edit->name }}" aria-describedby="emailHelp"
                                            placeholder="Nama" name="name">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="username">username</label>
                                        <input type="text" class="form-control @error('username')
                                                    is-invalid
                                            @enderror" id="username" value="{{ $edit->username }}"
                                            aria-describedby="emailHelp" placeholder="Username" name="username">
                                        @error('username')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <input type="email" class="form-control @error('email')
                                                    is-invalid
                                            @enderror" id="email" value="{{ $edit->email }}" aria-describedby="emailHelp"
                                            placeholder="Email" name="email">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="selectRole">Pilih Role</label>
                                        <select class="form-control select2 @error('role')
                                                   is-invalid
                                           @enderror" style="width: 100%;" id="selectRole" name="role">
                                            <option selected="selected">-- Pilih Role --</option>
                                            <option value="admin">Admin </option>
                                            <option value="ketua">Ketua</option>
                                            <option value="wakil">Wakil</option>
                                            <option value="bendahara">Bendahara</option>
                                            <option value="sekretaris">Sekretaris</option>
                                            <option value="panitia_qurban">Panitia Qurban</option>
                                            <option value="event_admin">Event Admin</option>
                                        </select>
                                        @error('role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </form>
                            </div>
                        </div>
                        <!-- /.card -->


                    </div>

                </div>

                {{-- /. Row --}}
            </div>
            <!-- /.container-fluid -->
        </div>
    </section>


@endsection


@section('script')
    <!-- jQuery -->
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
   
    <!-- Bootstrap 4 -->
    <script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
   

@endsection
