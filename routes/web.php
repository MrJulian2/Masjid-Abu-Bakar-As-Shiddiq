<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;

// USER CONTROLLER
use App\Http\Controllers\Users\KasController;
use App\Http\Controllers\EventUser\EventController;
use App\Http\Controllers\userAbout\AboutController;
// use App\Http\Controllers\Users\GaleryController;

// ADMIN CONTROLLER
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\Admin_Users\AdminusersController;
use App\Http\Controllers\Admin\Kas_Masjid\RekapController;
use App\Http\Controllers\Admin\Kas_Masjid\PemasukanController;
use App\Http\Controllers\Admin\Kas_Masjid\PengeluaranController;
use App\Http\Controllers\Admin\Kas_Masjid\SaldoweekController;
use App\Http\Controllers\Admin\Laporan\LapkasmasjidController;
use App\Http\Controllers\Admin\Admin_Profile_Setting\ProfilesettingController;
use App\Http\Controllers\Admin\Event_Admin\EventadminController;
use App\Http\Controllers\Admin\Khatib\khatibController;
use App\Http\Controllers\Admin\Photo_Masjid\PhotoController;
use App\Http\Controllers\Admin\Qurban\QurbanController;
use App\Http\Controllers\Admin\Qurban\QurbanPeriodeController;
use App\Http\Controllers\Admin\Takmir_Masjid\TakmirController;
use App\Http\Controllers\Admin\Zakat\ZakatMuzakkiController;
use App\Http\Controllers\Admin\Zakat\ZakatOpsiController;
use App\Http\Controllers\Admin\Zakat\ZakatPeriodeController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [KasController::class, 'index']);

Route::get('/about', [AboutController::class, 'index']);

Route::get('/event', [EventController::class, 'index']);
Route::get('/event/{id}', [EventController::class, 'show']);

// Route::get('/galery', [GaleryController::class, 'index']);

Route::get('/donate', function () {
    return view('User.About Us.donate');
});

Route::get('/service', function () {
    return view('User.About Us.service');
});

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (LARAVEL 10)
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->middleware(['auth'])
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('admin');

        /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */

        Route::resource('/users', AdminusersController::class)->middleware('admin:admin');

        /*
    |--------------------------------------------------------------------------
    | PHOTO
    |--------------------------------------------------------------------------
    */

        Route::resource('/photos', PhotoController::class)->middleware('admin:admin');

        /*
    |--------------------------------------------------------------------------
    | EVENT
    |--------------------------------------------------------------------------
    */

        Route::resource('/event', EventadminController::class);

        /*
    |--------------------------------------------------------------------------
    | TAKMIR & KHATIB
    |--------------------------------------------------------------------------
    */

        Route::resource('/takmir', TakmirController::class);

        Route::resource('/khatib', khatibController::class);

        /*
    |--------------------------------------------------------------------------
    | PROFILE SETTING
    |--------------------------------------------------------------------------
    */

        Route::get('/profile-setting', [ProfilesettingController::class, 'index']);
        Route::post('/profile-setting', [ProfilesettingController::class, 'update']);

        /*
    |--------------------------------------------------------------------------
    | KAS MASJID
    |--------------------------------------------------------------------------
    */

        Route::get('/kas-masjid/pemasukan', [PemasukanController::class, 'index'])->middleware('admin:admin|bendahara');

        Route::get('/kas-masjid/pengeluaran', [PengeluaranController::class, 'index'])->middleware('admin:admin|bendahara');

        Route::get('/kas-masjid/saldo', [SaldoweekController::class, 'index'])->middleware('admin:admin|bendahara');

        Route::get('/kas-masjid/rekap', [RekapController::class, 'index']);

        /*
    |--------------------------------------------------------------------------
    | AJAX PEMASUKAN
    |--------------------------------------------------------------------------
    */

        Route::get('/data-pemasukan', [PemasukanController::class, 'datapemasukan']);
        Route::post('/data-pemasukan/add', [PemasukanController::class, 'store']);
        Route::get('/data-pemasukan/edit/{id}', [PemasukanController::class, 'edit']);
        Route::put('/data-pemasukan/update/{id}', [PemasukanController::class, 'update']);
        Route::delete('/data-pemasukan/delete/{id}', [PemasukanController::class, 'destroy']);

        /*
    |--------------------------------------------------------------------------
    | AJAX PENGELUARAN
    |--------------------------------------------------------------------------
    */

        Route::get('/data-pengeluaran', [PengeluaranController::class, 'datapengeluaran']);
        Route::post('/data-pengeluaran/add', [PengeluaranController::class, 'store']);
        Route::get('/data-pengeluaran/edit/{id}', [PengeluaranController::class, 'edit']);
        Route::put('/data-pengeluaran/update/{id}', [PengeluaranController::class, 'update']);
        Route::delete('/data-pengeluaran/delete/{id}', [PengeluaranController::class, 'destroy']);

        /*
    |--------------------------------------------------------------------------
    | SALDO
    |--------------------------------------------------------------------------
    */

        Route::get('/data-saldo', [SaldoweekController::class, 'datasaldo']);
        Route::post('/data-saldo/add', [SaldoweekController::class, 'store']);
        Route::get('/data-saldo/edit/{id}', [SaldoweekController::class, 'edit']);
        Route::put('/data-saldo/update/{id}', [SaldoweekController::class, 'update']);
        Route::delete('/data-saldo/delete/{id}', [SaldoweekController::class, 'destroy']);

        /*
    |--------------------------------------------------------------------------
    | REKAP
    |--------------------------------------------------------------------------
    */

        Route::get('/data-rekap', [RekapController::class, 'datarekap']);

        /*
    |--------------------------------------------------------------------------
    | LAPORAN
    |--------------------------------------------------------------------------
    */

        Route::get('/laporan/kas-masjid', [LapkasmasjidController::class, 'index']);

        Route::get('/download-pdf', [LapkasmasjidController::class, 'DownloadSemuapdf']);

        Route::get('/download-pdf-periode', [LapkasmasjidController::class, 'DownloadPeriode'])->name('download-pdf-periode');

        /*
    |--------------------------------------------------------------------------
    | QURBAN
    |--------------------------------------------------------------------------
    */

        Route::get('/qurban', [QurbanController::class, 'index'])->name('qurban.index');

        Route::get('/qurban/add', [QurbanController::class, 'add'])->name('qurban.add');

        Route::post('/qurban/add', [QurbanController::class, 'store']);

        Route::get('/qurban/edit/{id}', [QurbanController::class, 'edit'])->name('qurban.edit');

        Route::put('/qurban/update/{id}', [QurbanController::class, 'update'])->name('qurban.update');

        Route::delete('/qurban/delete/{id}', [QurbanController::class, 'destroy'])->name('qurban.destroy');

        Route::get('/scan-qr', [QurbanController::class, 'scanPage'])->name('qurban.scan.page');

        Route::post('/scan-qr', [QurbanController::class, 'scanStore'])->name('qurban.scan.store');

        Route::get('/qurban/kupon', [QurbanController::class, 'kuponIndex'])->name('qurban.kupon.index');

        Route::get('/qurban/laporan/pdf', [QurbanController::class, 'exportPdf'])->name('qurban.laporan.pdf');

        Route::get('/qurban/validasi-manual', [QurbanController::class, 'validasiManual'])->name('qurban.validasi.manual');

        Route::post('/qurban/validasi-manual/{id}', [QurbanController::class, 'validasiManualProcess'])->name('qurban.validasi.manual.process');

        Route::post('/qurban/print-selected', [QurbanController::class, 'printSelected'])->name('qurban.print.selected');

        /*
    |--------------------------------------------------------------------------
    | PERIODE QURBAN
    |--------------------------------------------------------------------------
    */
        Route::get('/qurban-periode', [QurbanPeriodeController::class, 'index'])
            ->name('qurban-periode.index')
            ->middleware('admin:admin');

        Route::post('/qurban-periode', [QurbanPeriodeController::class, 'store'])
            ->name('qurban-periode.store')
            ->middleware('admin:admin');

        Route::put('/qurban-periode/{qurbanPeriode}', [QurbanPeriodeController::class, 'update'])
            ->name('qurban-periode.update')
            ->middleware('admin:admin');

        Route::delete('/qurban-periode/{qurbanPeriode}', [QurbanPeriodeController::class, 'destroy'])
            ->name('qurban-periode.destroy')
            ->middleware('admin:admin');

        Route::put('/qurban-periode/{id}/aktifkan', [QurbanPeriodeController::class, 'aktifkan'])
            ->name('qurban-periode.aktifkan')
            ->middleware('admin:admin');

        /*
    |--------------------------------------------------------------------------
    | Zakat Fitrah
    |--------------------------------------------------------------------------
    */

        Route::get('/zakat-muzakki', [ZakatMuzakkiController::class, 'index'])->name('zakat-muzakki.index');

        Route::post('/zakat-muzakki', [ZakatMuzakkiController::class, 'store'])->name('zakat-muzakki.store');

        Route::put('/zakat-muzakki/{id}', [ZakatMuzakkiController::class, 'update'])->name('zakat-muzakki.update');

        Route::delete('/zakat-muzakki/{id}', [ZakatMuzakkiController::class, 'destroy'])->name('zakat-muzakki.destroy');

        Route::get('/dashboard-zakat', [ZakatMuzakkiController::class, 'dashboard'])->name('zakat.dashboard');

        /*
        |--------------------------------------------------------------------------
        | OPSI ZAKAT
        |--------------------------------------------------------------------------
        */

        Route::get('/zakat-opsi', [ZakatOpsiController::class, 'index'])->name('zakat-opsi.index');
        Route::post('/zakat-opsi', [ZakatOpsiController::class, 'store'])->name('zakat-opsi.store');
        Route::put('/zakat-opsi/{id}', [ZakatOpsiController::class, 'update'])->name('zakat-opsi.update');
        Route::delete('/zakat-opsi/{id}', [ZakatOpsiController::class, 'destroy'])->name('zakat-opsi.destroy');
        Route::put('/zakat-opsi/{id}/aktifkan', [ZakatOpsiController::class, 'aktifkan'])->name('zakat-opsi.aktifkan');
        Route::put('/zakat-opsi/{id}/nonaktifkan', [ZakatOpsiController::class, 'nonaktifkan'])->name('zakat-opsi.nonaktifkan');

        /*
        |--------------------------------------------------------------------------
        | PERIODE ZAKAT
        |--------------------------------------------------------------------------
        */

        Route::get('/zakat-periode', [ZakatPeriodeController::class, 'index'])
            ->name('zakat-periode.index')
            ->middleware('admin:admin');

        Route::post('/zakat-periode', [ZakatPeriodeController::class, 'store'])
            ->name('zakat-periode.store')
            ->middleware('admin:admin');

        Route::put('/zakat-periode/{zakatPeriode}', [ZakatPeriodeController::class, 'update'])
            ->name('zakat-periode.update')
            ->middleware('admin:admin');

        Route::delete('/zakat-periode/{zakatPeriode}', [ZakatPeriodeController::class, 'destroy'])
            ->name('zakat-periode.destroy')
            ->middleware('admin:admin');

        Route::put('/zakat-periode/{id}/aktifkan', [ZakatPeriodeController::class, 'aktifkan'])
            ->name('zakat-periode.aktifkan')
            ->middleware('admin:admin');
    });

/*
|--------------------------------------------------------------------------
| CLEAR CACHE
|--------------------------------------------------------------------------
*/

Route::get('/clear', function () {
    Artisan::call('optimize:clear');

    return 'CACHE CLEARED';
});

/*
|--------------------------------------------------------------------------
| CACHE BUILD
|--------------------------------------------------------------------------
*/

Route::get('/setup', function () {
    Artisan::call('config:cache');
    Artisan::call('route:cache');
    Artisan::call('view:cache');

    return 'SETUP DONE';
});
