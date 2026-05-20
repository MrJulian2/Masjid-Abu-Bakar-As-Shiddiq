@extends('admin.index')

@section('head')
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet"
        href="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- daterange picker -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Tambah Data Penerima Qurban</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Qurban</a></li>
                        <li class="breadcrumb-item active">Tambah</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <section class="content">
        <div class="container-fluid">

            <div class="row">

                <div class="col-md-12">
                    <div class="card card-primary">

                        <div class="card-header">
                            <h3 class="card-title">Input Penerima Qurban</h3>
                        </div>

                        <div class="card-body">

                            {{-- FORM INPUT --}}
                            <form action="{{ url('/admin/qurban/add') }}" method="POST">
                                @csrf

                                <div class="row">

                                    <div class="form-group col-md-6">
                                        <label>Nama</label>
                                        <input type="text" name="nama" class="form-control" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label>No HP</label>
                                        <input type="text" name="no_hp" class="form-control">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label>Alamat</label>
                                        <textarea name="alamat" class="form-control" rows="3">-</textarea>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>RT</label>
                                        <input type="text" name="rt" class="form-control" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>RW</label>
                                        <input type="text" name="rw" class="form-control" required>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label>Jumlah Kupon</label>
                                        <input type="number" name="jumlah_kupon" class="form-control" value="1"
                                            min="1" required>
                                    </div>

                                </div>

                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Simpan & Generate Kupon
                                </button>

                            </form>

                        </div>

                    </div>
                </div>

            </div>

         

        </div>
    </section>
@endsection


@section('script')
    <!-- jQuery -->
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>
    <!-- InputMask/Moment JS -->
    <script src="{{ asset('AdminLTE/plugins/moment/moment.min.js') }}"></script>
    <!-- date-range-picker -->
    <script src="{{ asset('AdminLTE/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('AdminLTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>

    <script>
        //  Timepicker
        $('#reservationdate1,#reservationdate2').datetimepicker({
            format: 'DD-MM-YYYY',
        });

        // $('#reservationdate1,#reservationdate2').datetimepicker({
        //     changeMonth: true,
        //     changeYear: true,
        //     yearRange: '2011:2037',
        //     Format: 'dd/mm/yy',
        //     minDate: 0,
        //     defaultDate: null
        // }).on('change', function() {
        //     if($(this).valid()){
        //    $(this).removeClass('invalid').addClass('success');   
        // }else{
        //     $(this).addClass('invalid');
        // }
        // });
    </script>
    {{-- 
<script>
    $(document).on('click','#cetak_periode', function () {
       
   
        var data = {
            'tglawal': $('#tgl_awal').val(),
            'tglakhir': $('#tgl_akhir').val(),
        }
        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
        $.ajax({
            type: "GET",
            url: "/download-pdf-periode/",
            data: data,
            dataType: "json",
            success: function (response) {
                alert('Berhasil');
            }
        });
        
    });
</script> --}}
@endsection