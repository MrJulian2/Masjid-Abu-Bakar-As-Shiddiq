<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masjid Abu Bakar As-Shiddiq | Login</title>

    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/fontawesome-free/css/all.min.css') }}">

    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

    <!-- AdminLTE -->
    <link rel="stylesheet" href="{{ asset('AdminLTE/dist/css/adminlte.min.css') }}">

    <!-- Vite -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
</head>

<body class="hold-transition login-page">

    <div class="login-box">

        <div class="login-logo">
            <a href="{{ url('/') }}">
                <h2>
                    Masjid Abu Bakar <br>
                    As-Shiddiq
                </h2>
            </a>
        </div>

        <!-- Login Card -->
        <div class="card">

            <div class="card-body login-card-body">

                <p class="login-box-msg">
                    Login Terlebih Dahulu
                </p>

                {{-- Session Error --}}
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Validation Errors --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- USERNAME --}}
                    <div class="input-group mb-3">

                        <input id="username"
                            type="text"
                            class="form-control @error('username') is-invalid @enderror"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            autofocus
                            placeholder="Username">

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>

                        @error('username')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>

                    {{-- PASSWORD --}}
                    <div class="input-group mb-3">

                        <input id="password"
                            type="password"
                            class="form-control @error('password') is-invalid @enderror"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Password">

                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>

                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                    </div>

                    <div class="row">

                        {{-- Remember Me --}}
                        <div class="col-8">

                            <div class="icheck-primary">

                                <input type="checkbox"
                                    name="remember"
                                    id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>

                                <label for="remember">
                                    Remember Me
                                </label>

                            </div>

                        </div>

                        {{-- Login Button --}}
                        <div class="col-4">

                            <button type="submit"
                                class="btn btn-primary btn-block">
                                Login
                            </button>

                        </div>

                    </div>

                </form>

                {{-- Forgot Password --}}
                @if (Route::has('password.request'))

                    <p class="mb-1 mt-3">

                        <a href="{{ route('password.request') }}">
                            Forgot Your Password?
                        </a>

                    </p>

                @endif

            </div>

        </div>

    </div>

    <!-- jQuery -->
    <script src="{{ asset('AdminLTE/plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap -->
    <script src="{{ asset('AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- AdminLTE -->
    <script src="{{ asset('AdminLTE/dist/js/adminlte.min.js') }}"></script>

</body>

</html>