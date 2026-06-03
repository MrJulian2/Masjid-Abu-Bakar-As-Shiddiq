<?php

namespace App\Http\Controllers\Admin\Qurban;

use App\Http\Controllers\Controller;
use App\Models\QurbanPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class QurbanPeriodeController extends Controller
{
    public function index()
    {
        $periodes = QurbanPeriode::orderBy('tahun', 'desc')->get();

        return view('admin.Qurban.periode', compact('periodes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'nama'  => 'required|string|max:255',
        ]);

        QurbanPeriode::create([
            'tahun' => $request->tahun,
            'nama'  => $request->nama,
            'aktif' => false,
        ]);

        Alert::success('Berhasil', 'Periode berhasil ditambahkan');
        return back();
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun' => 'required|integer',
            'nama'  => 'required|string|max:255',
        ]);

        $periode = QurbanPeriode::findOrFail($id);

        $periode->update([
            'tahun' => $request->tahun,
            'nama'  => $request->nama,
        ]);

        Alert::success('Berhasil', 'Periode berhasil diupdate');
        return back();
    }

    public function destroy($id)
    {
        $periode = QurbanPeriode::findOrFail($id);

        // jangan hapus kalau aktif
        if ($periode->aktif) {
            Alert::error('Gagal', 'Periode aktif tidak bisa dihapus');
            return back();
        }

        $periode->delete();

        Alert::success('Berhasil', 'Periode berhasil dihapus');
        return back();
    }

    public function aktifkan($id)
    {
        DB::transaction(function () use ($id) {

            // matikan semua
            QurbanPeriode::query()->update(['aktif' => false]);

            // aktifkan yg dipilih
            $periode = QurbanPeriode::findOrFail($id);
            $periode->update(['aktif' => true]);
        });

        Alert::success('Berhasil', 'Periode berhasil diaktifkan');
        return back();
    }
}