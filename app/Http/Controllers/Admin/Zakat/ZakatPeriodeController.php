<?php

namespace App\Http\Controllers\Admin\Zakat;


use App\Http\Controllers\Controller;
use App\Models\ZakatPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ZakatPeriodeController extends Controller
{
    public function index()
    {
        $periodes = ZakatPeriode::orderBy('tahun', 'desc')->get();

        return view('admin.Zakat.periode', compact('periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'nama'  => 'required|string|max:255',
        ]);

        ZakatPeriode::create([
            'tahun' => $request->tahun,
            'nama'  => $request->nama,
            'aktif' => false,
        ]);

        Alert::success('Berhasil', 'Periode zakat fitrah berhasil ditambahkan');
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'nama'  => 'required|string|max:255',
        ]);

        $periode = ZakatPeriode::findOrFail($id);

        $periode->update([
            'tahun' => $request->tahun,
            'nama'  => $request->nama,
        ]);

        Alert::success('Berhasil', 'Periode zakat fitrah berhasil diupdate');
        return back();
    }

    public function destroy($id)
    {
        $periode = ZakatPeriode::findOrFail($id);

        // jangan hapus kalau aktif
        if ($periode->aktif) {
            Alert::error('Gagal', 'Periode aktif tidak bisa dihapus');
            return back();
        }

        $periode->delete();

        Alert::success('Berhasil', 'Periode zakat fitrah berhasil dihapus');
        return back();
    }

    public function aktifkan($id)
    {
        DB::transaction(function () use ($id) {

            // matikan semua
            ZakatPeriode::query()->update(['aktif' => false]);

            // aktifkan yg dipilih
            $periode = ZakatPeriode::findOrFail($id);
            $periode->update(['aktif' => true]);
        });

        Alert::success('Berhasil', 'Periode zakat fitrah berhasil diaktifkan');
        return back();
    }
}