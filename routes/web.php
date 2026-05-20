<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\users\KasController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\Kas_Masjid\RekapController;
use App\Http\Controllers\Admin\Kas_Masjid\PemasukanController;
use App\Http\Controllers\Admin\Laporan\LapkasmasjidController;
use App\Http\Controllers\Admin\Admin_Users\AdminusersController;
use App\Http\Controllers\Admin\Kas_Masjid\PengeluaranController;
use App\Http\Controllers\Admin\Admin_Profile_Setting\ProfilesettingController;
use App\Http\Controllers\Admin\Event_Admin\EventadminController;
use App\Http\Controllers\Admin\Kas_Masjid\SaldoweekController;
use App\Http\Controllers\Admin\Khatib\khatibController;
use App\Http\Controllers\Admin\Photo_Masjid\PhotoController;
use App\Http\Controllers\Admin\Takmir_Masjid\TakmirController;
use App\Http\Controllers\EventUser\EventController;
use App\Http\Controllers\QurbanController;
use App\Http\Controllers\userAbout\AboutController;
use App\Http\Controllers\Users\GaleryController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [KasController::class, 'index']);
// Route::get('/', function () {
//     return view('User.PartialsUser.home');
// });
// Route::get('/galery',[GaleryController::class,'index']);

Route::get('/about', [AboutController::class, 'index']);

//event
Route::get('/event', [EventController::class, 'index']);
Route::get('/event/{id}', [EventController::class, 'Show']);

Route::get('/donate', function () {
    return view('User.About Us.donate');
});
Route::get('/service', function () {
    return view('User.About Us.service');
});

Auth::routes();
Auth::routes(['verify' => true]);

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('admin');
    //Admin Users
    // Route::get('/admin-users', [AdminusersController::class,'index']);
    // Route::get('/admin-users/create', [AdminusersController::class,'create']);
    // Route::get('/admin-users/add', [AdminusersController::class,'store']);
    // Route::get('/admin-users/edit', [AdminusersController::class,'edit']);
    // Route::get('/admin-users/edit/{id}', [AdminusersController::class,'update']);
    // Route::get('/admin-users/hapus/{id}', [AdminusersController::class,'destroy']);
    Route::resource('/users', AdminusersController::class)->middleware('admin:admin');

    Route::resource('/photos', PhotoController::class)->middleware('admin:admin');
    //event

    Route::resource('/event', EventadminController::class);
    // Profile Setting

    Route::resource('/takmir', TakmirController::class);

    Route::resource('/khatib', khatibController::class);

    // Route::resource('/profile-setting', ProfilesettingController::class);

    Route::get('/profile-setting', [ProfilesettingController::class, 'index']);
    Route::post('/profile-setting', [ProfilesettingController::class, 'update']);
    // Route::post('/profile-setting',[ProfilesettingController::class,'store']);

    Route::get('/kas-masjid/pemasukan', [PemasukanController::class, 'index'])->middleware('admin:admin|bendahara');
    Route::get('/kas-masjid/pengeluaran', [PengeluaranController::class, 'index'])->middleware('admin:admin|bendahara');
    Route::get('/kas-masjid/saldo', [SaldoweekController::class, 'index'])->middleware('admin:admin|bendahara');
    Route::get('/kas-masjid/rekap', [RekapController::class, 'index']);
    //AJAX JSON
    //Pemasukan
    Route::get('/data-pemasukan', [PemasukanController::class, 'datapemasukan'])->middleware('admin:admin|bendahara');
    Route::post('/data-pemasukan/add', [PemasukanController::class, 'store'])->middleware('admin:admin|bendahara');
    Route::get('/data-pemasukan/edit/{id}', [PemasukanController::class, 'edit'])->middleware('admin:admin|bendahara');
    Route::put('/data-pemasukan/update/{id}', [PemasukanController::class, 'update'])->middleware('admin:admin|bendahara');
    Route::delete('/data-pemasukan/delete/{id}', [PemasukanController::class, 'destroy'])->middleware('admin:admin|bendahara');
    //Pengeluaran
    Route::get('/data-pengeluaran', [PengeluaranController::class, 'datapengeluaran'])->middleware('admin:admin|bendahara');
    Route::post('/data-pengeluaran/add', [PengeluaranController::class, 'store'])->middleware('admin:admin|bendahara');
    Route::get('/data-pengeluaran/edit/{id}', [PengeluaranController::class, 'edit'])->middleware('admin:admin|bendahara');
    Route::put('/data-pengeluaran/update/{id}', [PengeluaranController::class, 'update'])->middleware('admin:admin|bendahara');
    Route::delete('/data-pengeluaran/delete/{id}', [PengeluaranController::class, 'destroy'])->middleware('admin:admin|bendahara');
    //Saldo / minggu
    Route::get('/data-saldo', [SaldoweekController::class, 'datasaldo'])->middleware('admin:admin|bendahara');
    Route::post('/data-saldo/add', [SaldoweekController::class, 'store'])->middleware('admin:admin|bendahara');
    Route::get('/data-saldo/edit/{id}', [SaldoweekController::class, 'edit'])->middleware('admin:admin|bendahara');
    Route::put('/data-saldo/update/{id}', [SaldoweekController::class, 'update'])->middleware('admin:admin|bendahara');
    Route::delete('/data-saldo/delete/{id}', [SaldoweekController::class, 'destroy'])->middleware('admin:admin|bendahara');

    // Rekap kas-masjid
    Route::get('/data-rekap', [RekapController::class, 'datarekap']);

    // Laporan Kas Masjid
    Route::get('/laporan/kas-masjid', [LapkasmasjidController::class, 'index']);
    Route::get('/download-pdf', [LapkasmasjidController::class, 'DownloadSemuapdf']);
    Route::get('/download-pdf-periode', [LapkasmasjidController::class, 'DownloadPeriode'])->name('download-pdf-periode');

    // Qurban

    Route::get('/qurban', [QurbanController::class, 'index'])->name('qurban.index');
    Route::get('/qurban/add', [QurbanController::class, 'add'])->name('qurban.add');
    Route::post('/qurban/add', [QurbanController::class, 'store']);
    Route::get('/qurban/edit/{id}', [QurbanController::class, 'edit'])->name('qurban.edit');
    Route::put('/qurban/update/{id}', [QurbanController::class, 'update'])
        ->name('qurban.update')
        ->middleware('admin:admin');
    Route::delete('/qurban/delete/{id}', [QurbanController::class, 'destroy'])
        ->name('qurban.destroy')
        ->middleware('admin:admin');
    Route::get('/scan-qr', [QurbanController::class, 'scanPage'])->name('qurban.scan.page');
    Route::post('/scan-qr', [QurbanController::class, 'scanStore'])->name('qurban.scan.store');

    Route::get('/qurban/kupon', [QurbanController::class, 'kuponIndex'])->name('qurban.kupon.index');
    Route::get('/qurban/laporan/pdf', [QurbanController::class, 'exportPdf'])->name('qurban.laporan.pdf');

    Route::get('/qurban/validasi-manual', [QurbanController::class, 'validasiManual'])
        ->name('qurban.validasi.manual');

    Route::post('/qurban/validasi-manual/{id}', [QurbanController::class, 'validasiManualProcess'])
        ->name('qurban.validasi.manual.process');
});

Route::get('/setup', function () {
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');

    return 'DONE'; //Return anything
});

Route::get('/clear', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');

    return 'DONE'; //Return anything
});

// Route::get('/symlink', function () {

//     $link = Artisan::call('storage:link');

//     if ($link) {
//         return "yes";
//     }

//     return "nope";
// });

// require __DIR__ . '/auth.php';
