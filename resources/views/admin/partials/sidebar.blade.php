 <!-- Main Sidebar Container -->
 <aside class="main-sidebar sidebar-dark-primary elevation-4">
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
                     <a href=" {{ url('/admin') }}" class="nav-link">
                         <i class="nav-icon fas fa-tachometer-alt"></i>
                         <p>
                             Home
                         </p>
                     </a>
                 </li>
                 @if (Auth::user()->role == 'admin')
                     {{-- @if (Auth::user()->role == 'admin') --}}
                     <li class="nav-item">
                         <a href="{{ route('users.index') }}" class="nav-link">
                             <i class="fas fa-user-tie"></i>
                             <p>
                                 Tambah Admin User
                             </p>
                         </a>
                     </li>
                     {{-- @endif --}}
                     <li class="nav-item">
                         <a href="{{ route('event.index') }}" class="nav-link">
                             <i class="fas fa-calendar-alt"></i>
                             <p>
                                 Tambah Event
                             </p>
                         </a>
                     </li>
                     <li class="nav-item">
                         <a href="{{ route('photos.index') }}" class="nav-link">
                             <i class="far fa-images"></i>
                             <p>
                                 Tambah Photo Masjid
                             </p>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a href="{{ route('takmir.index') }}" class="nav-link">
                             <i class="fas fa-users"></i>
                             <p>
                                 Tambah Pengurus Takmir
                             </p>
                         </a>
                     </li>

                     <li class="nav-item">
                         <a href="{{ route('khatib.index') }}" class="nav-link">
                             <i class="fas fa-users"></i>
                             <p>
                                 Tambah Jadwal Khatib
                             </p>
                         </a>
                     </li>

                     {{-- Kas Masjid --}}
                     <li class="nav-item">
                         <a href="#" class="nav-link">
                             <i class="nav-icon fas fa-mosque"></i>
                             <p>
                                 Kas Masjid
                                 <i class="fas fa-angle-left right"></i>
                             </p>
                         </a>
                         <ul class="nav nav-treeview">
                             {{-- @if (Auth::user()->role == 'admin' || Auth::user()->role == 'bendahara') --}}
                             <li class="nav-item">
                                 <a href=" {{ url('admin/kas-masjid/saldo') }}" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p> Saldo / Minggu </p>
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href=" {{ url('admin/kas-masjid/pemasukan') }}" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p> Pemasukan </p>
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="{{ url('admin/kas-masjid/pengeluaran') }}" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p> Pengeluaran </p>
                                 </a>
                             </li>
                             {{-- @endif --}}
                             <li class="nav-item">
                                 <a href="{{ url('admin/kas-masjid/rekap') }}" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p>Rekap Kas Masjid</p>
                                 </a>
                             </li>
                         </ul>
                     </li>
                     {{-- Kas Sosial --}}
                     {{-- @if (Auth::user()->role == 'admin') --}}
                     <li class="nav-item">
                         <a href="#" class="nav-link">
                             <i class="nav-icon fas fa-hand-holding-usd"></i>
                             <p>
                                 Kas Sosial
                                 <i class="fas fa-angle-left right"></i>
                             </p>
                         </a>
                         <ul class="nav nav-treeview">
                             <li class="nav-item">
                                 <a href="#" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p> Pemasukan </p>
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="#" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p> Pengeluaran </p>
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="#" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p>Rekap Kas Masjid</p>
                                 </a>
                             </li>
                         </ul>
                     </li>
                     {{-- @endif --}}

                     {{-- Laporan --}}
                     <li class="nav-item">
                         <a href="#" class="nav-link">
                             <i class="nav-icon fas fa-book"></i>
                             <p>
                                 Laporan
                                 <i class="fas fa-angle-left right"></i>
                             </p>
                         </a>
                         <ul class="nav nav-treeview">
                             <li class="nav-item">
                                 <a href=" {{ url('admin/laporan/kas-masjid') }}" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p> Kas-Masjid </p>
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="#" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p> Kas-Sosial </p>
                                 </a>
                             </li>
                         </ul>
                     </li>
                 @endif
                 {{-- Qurban --}}

                 <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="fas fa-paw"></i>
                         <p>
                             Qurban
                             <i class="fas fa-angle-left right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href=" {{ url('admin/qurban/add') }}" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p> Tambah Penerima</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="{{ route('qurban.index') }}" class="nav-link">
                                 <i class="far fa-circle nav-icon"></i>
                                 <p> Kelola Penerima</p>
                             </a>
                         </li>
                         @if (Auth::user()->role === 'admin')
                             <li class="nav-item">
                                 <a href="{{ route('qurban.scan.page') }}" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p> Scan Qr</p>
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="{{ route('qurban.kupon.index') }}" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p> Kupon dan Laporan </p>
                                 </a>
                             </li>
                             <li class="nav-item">
                                 <a href="{{ route('qurban.validasi.manual') }}" class="nav-link">
                                     <i class="far fa-circle nav-icon"></i>
                                     <p> Validasi Manual </p>
                                 </a>
                             </li>
                         @endif
                     </ul>
                 </li>

                 <li class="nav-item">
                     <a href="{{ url('admin/profile-setting') }}" class="nav-link">
                         <i class="fas fa-user-cog"></i>
                         <p>
                             Profile Setting
                         </p>
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
