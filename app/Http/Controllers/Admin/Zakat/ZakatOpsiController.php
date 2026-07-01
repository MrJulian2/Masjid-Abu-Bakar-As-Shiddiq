<?php

namespace App\Http\Controllers\Admin\Zakat;

use App\Http\Controllers\Controller;
use App\Models\zakatopsi;
use App\Models\ZakatPeriode;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class ZakatOpsiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periodeAktif = ZakatPeriode::where('aktif', true)->first();

        if (!$periodeAktif) {
            Alert::error('Gagal', 'Tidak ada periode zakat aktif. Silakan aktifkan periode terlebih dahulu.');
            return redirect('admin/zakat-periode');
        }

        $opsis = zakatopsi::where('periode_id', $periodeAktif->id)->get();

        return view('admin.Zakat.setting', compact('opsis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $periodeAktif = ZakatPeriode::where('aktif', true)->first();
        
        if (!$periodeAktif) {
            Alert::error('Gagal', 'Tidak ada periode zakat aktif. Silakan aktifkan periode terlebih dahulu.');
            return redirect()->back();
        }

        $request->validate([
            'jenis' => 'required|in:beras,uang',
        ]);

        if ($request->jenis == 'beras') {

            $request->validate([
                'nilai_beras' => 'required|numeric|min:0.1',
            ]);

        } else {

            $request->validate([
                'nilai_uang' => 'required|numeric|min:1',
            ]);

        }

        zakatopsi::create([
            'jenis' => $request->jenis,

            'nilai_beras' => $request->jenis == 'beras'
                ? $request->nilai_beras
                : null,

            'nilai_uang' => $request->jenis == 'uang'
                ? $request->nilai_uang
                : null,

            'aktif' => true,
            'periode_id' => $periodeAktif->id,
        ]);

        Alert::success('Berhasil', 'Opsi zakat berhasil ditambahkan');

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'jenis' => 'required|in:beras,uang',
        ]);

        if ($request->jenis == 'beras') {

            $request->validate([
                'nilai_beras' => 'required|numeric|min:0.1',
            ]);

        } else {

            $request->validate([
                'nilai_uang' => 'required|numeric|min:1',
            ]);

        }

        $opsi = zakatopsi::findOrFail($id);

        $opsi->update([
            'jenis' => $request->jenis,

            'nilai_beras' => $request->jenis == 'beras'
                ? $request->nilai_beras
                : null,

            'nilai_uang' => $request->jenis == 'uang'
                ? $request->nilai_uang
                : null,
        ]);

        Alert::success('Berhasil', 'Opsi zakat berhasil diperbarui');

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $opsi = zakatopsi::findOrFail($id);

        $opsi->delete();

        Alert::success('Berhasil', 'Opsi zakat berhasil dihapus');

        return redirect()->back();
    }

    /**
     * Aktifkan data.
     */
    public function aktifkan($id)
    {
        $opsi = zakatopsi::findOrFail($id);

        $opsi->update([
            'aktif' => true,
        ]);

        Alert::success('Berhasil', 'Opsi zakat berhasil diaktifkan');

        return redirect()->back();
    }

    /**
     * Nonaktifkan data.
     */
    public function nonaktifkan($id)
    {
        $opsi = zakatopsi::findOrFail($id);

        $opsi->update([
            'aktif' => false,
        ]);

        Alert::success('Berhasil', 'Opsi zakat berhasil dinonaktifkan');

        return redirect()->back();
    }


}
