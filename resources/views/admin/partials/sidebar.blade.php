@php
    $isHome = request()->is('admin');

    $isUsers = Route::is('users.*');
    $isEvent = Route::is('event.*');
    $isPhotos = Route::is('photos.*');
    $isTakmir = Route::is('takmir.*');
    $isKhatib = Route::is('khatib.*');

    $isKasMasjid = request()->is('admin/kas-masjid*');
    $isKasSosial = request()->is('admin/kas-sosial*'); // ← tambah ini
    $isLaporan = request()->is('admin/laporan*');
    $isQurban = request()->is('admin/qurban*') || Route::is('qurban.*');
    $isProfile = request()->is('admin/profile-setting');
@endphp
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-masjid elevation-4">
    <!-- Brand Logo -->
    <a href=" {{ url('/admin') }} " class="brand-link">
        <img src="{{ asset('image/logo-masjid.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
            style="opacity: .8">
        <span class="brand-text font-weight-light">Abu Bakar As-Shiddiq</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        ` <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ '/storage/' . Auth::user()->profile_photo_path }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ Auth::user()->name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="{{ url('/admin') }}" class="nav-link {{ $isHome ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                @if (Auth::user()->role == 'admin')
                    {{-- @if (Auth::user()->role == 'admin') --}}
                    <li class="nav-item">
                        <a href="{{ route('users.index') }}" class="nav-link {{ $isUsers ? 'active' : '' }}">
                            <i class="fas fa-user-tie"></i>
                            <p>
                                Tambah Admin User
                            </p>
                        </a>
                    </li>
                    {{-- @endif --}}
                    <li class="nav-item">
                        <a href="{{ route('event.index') }}" class="nav-link {{ $isEvent ? 'active' : '' }}">
                            <i class="fas fa-calendar-alt"></i>
                            <p>
                                Tambah Event
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('photos.index') }}" class="nav-link {{ $isPhotos ? 'active' : '' }}">
                            <i class="far fa-images"></i>
                            <p>
                                Tambah Photo Masjid
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('takmir.index') }}" class="nav-link {{ $isTakmir ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <p>
                                Tambah Pengurus Takmir
                            </p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('khatib.index') }}" class="nav-link {{ $isKhatib ? 'active' : '' }}">
                            <i class="fas fa-users"></i>
                            <p>
                                Tambah Jadwal Khatib
                            </p>
                        </a>
                    </li>

                    {{-- Kas Masjid --}}
                    <li class="nav-item {{ $isKasMasjid ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isKasMasjid ? 'active' : '' }}">
                            <i class="nav-icon fas fa-mosque"></i>
                            <p>
                                Kas Masjid
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            {{-- @if (Auth::user()->role == 'admin' || Auth::user()->role == 'bendahara') --}}
                            <li class="nav-item">
                                <a href="{{ url('admin/kas-masjid/saldo') }}"
                                    class="nav-link {{ request()->is('admin/kas-masjid/saldo') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> Saldo / Minggu </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/kas-masjid/pemasukan') }}"
                                    class="nav-link {{ request()->is('admin/kas-masjid/pemasukan') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> Pemasukan </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/kas-masjid/pengeluaran') }}"
                                    class="nav-link {{ request()->is('admin/kas-masjid/pengeluaran') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> Pengeluaran </p>
                                </a>
                            </li>
                            {{-- @endif --}}
                            <li class="nav-item">
                                <a href="{{ url('admin/kas-masjid/rekap') }}"
                                    class="nav-link {{ request()->is('admin/kas-masjid/rekap') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rekap Kas Masjid</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    {{-- Kas Sosial --}}
                    {{-- @if (Auth::user()->role == 'admin') --}}
                    {{-- <li class="nav-item {{ $isKasSosial ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isKasSosial ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hand-holding-usd"></i>
                            <p>
                                Kas Sosial
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/kas-sosial/pemasukan') }}"
                                    class="nav-link {{ request()->is('admin/kas-sosial/pemasukan') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> Pemasukan </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/kas-sosial/pengeluaran') }}"
                                    class="nav-link {{ request()->is('admin/kas-sosial/pengeluaran') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> Pengeluaran </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('admin/kas-sosial/rekap') }}"
                                    class="nav-link {{ request()->is('admin/kas-sosial/rekap') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Rekap Kas Sosial</p>
                                </a>
                            </li>
                        </ul>
                    </li> --}}
                    {{-- @endif --}}

                    {{-- Laporan --}}
                    <li class="nav-item {{ $isLaporan ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ $isLaporan ? 'active' : '' }}">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Laporan
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ url('admin/laporan/kas-masjid') }}"
                                    class="nav-link {{ request()->is('admin/laporan/kas-masjid') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> Kas-Masjid </p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="{{ url('admin/laporan/kas-sosial') }}"
                                    class="nav-link {{ request()->is('admin/laporan/kas-sosial') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p> Kas-Sosial </p>
                                </a>
                            </li> --}}
                        </ul>
                    </li>
                @endif
                {{-- Qurban --}}

                <li class="nav-item {{ $isQurban ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ $isQurban ? 'active' : '' }}">
                        <i class="fas fa-paw"></i>
                        <p>
                            Qurban
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('admin/qurban/add') }}"
                                class="nav-link {{ request()->is('admin/qurban/add') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Tambah Penerima</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('qurban.index') }}"
                                class="nav-link {{ Route::is('qurban.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Kelola Penerima</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('qurban.scan.page') }}"
                                class="nav-link {{ Route::is('qurban.scan.page') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Scan Qr</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('qurban.kupon.index') }}"
                                class="nav-link {{ Route::is('qurban.kupon.index') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Kupon dan Laporan </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('qurban.validasi.manual') }}"
                                class="nav-link {{ Route::is('qurban.validasi.manual') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Validasi Manual </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="{{ url('admin/profile-setting') }}" class="nav-link {{ $isProfile ? 'active' : '' }}">
                        <i class="fas fa-user-cog"></i>
                        <p>Profile Setting</p>
                    </a>
                </li>



                <li class="nav-item">
                    <a class="nav-link" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i>
                        {{ __('Logout') }}

                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>




        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
<style>
    /* Sidebar utama */
    .sidebar-masjid {
        background: #14532d !important;
    }

    /* Logo area */
    .sidebar-masjid .brand-link {
        border-bottom: 1px solid rgba(255, 255, 255, .1);
        color: #fff !important;
    }

    /* Nama user */
    .sidebar-masjid .user-panel .info a {
        color: #fff !important;
    }

    /* Menu */
    .sidebar-masjid .nav-link {
        color: #d1fae5 !important;
    }

    /* Hover menu */
    .sidebar-masjid .nav-link:hover {
        background: #166534 !important;
        color: #fff !important;
    }

    /* Menu aktif */
    .sidebar-masjid .nav-link.active {
        background: #22c55e !important;
        color: #fff !important;
        font-weight: 600;
    }

    /* Search box */
    .sidebar-masjid .form-control-sidebar {
        background: #166534;
        color: #fff;
        border: none;
    }

    .sidebar-masjid .btn-sidebar {
        background: #166534;
        color: #fff;
    }
</style>
