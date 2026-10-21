<?php

namespace App\Http\Controllers\Admin\Zakat;

use App\Http\Controllers\Controller;
use App\Models\Mustahik;
use App\Models\ZakatPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RealRashid\SweetAlert\Facades\Alert;

class MustahikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periodeAktif = ZakatPeriode::where('aktif', 1)->first();

        return view('admin.Zakat.mustahik-tambah', compact('periodeAktif'));
    }

    /**
     * Store a newly created resource in storage.
     */
     public function store(Request $request)
    {
        $request->validate([
            'periode_id' => 'required|integer|exists:zakat_periodes,id',
            'nama'       => 'required|string|max:255',
            'nomor_hp'   => 'nullable|string|max:20',
            'alamat'     => 'required|string',
            'rt'         => 'nullable|string|max:10',
            'rw'         => 'nullable|string|max:10',
        ], [
            'periode_id.required' => 'Periode zakat wajib tersedia.',
            'periode_id.exists'   => 'Periode zakat tidak ditemukan.',
            'nama.required'       => 'Nama mustahik wajib diisi.',
            'nama.max'            => 'Nama mustahik maksimal 255 karakter.',
            'alamat.required'     => 'Alamat mustahik wajib diisi.',
        ]);

        try {
            // Pastikan data masuk ke periode yang sedang aktif.
            $periodeAktif = ZakatPeriode::where('id', $request->periode_id)
                ->where('aktif', 1)
                ->first();

            if (!$periodeAktif) {
                return back()
                    ->withInput()
                    ->with('error', 'Periode zakat tidak aktif atau sudah berubah.');
            }

            Mustahik::create([
                'periode_id' => $periodeAktif->id,
                'nama'       => $request->nama,
                'nomor_hp'   => $request->nomor_hp,
                'alamat'     => $request->alamat,
                'rt'         => $request->rt,
                'rw'         => $request->rw,
            ]);

            Alert::success('Berhasil', 'Data mustahik berhasil disimpan');
            return redirect()->route('zakat-mustahik.create');

        } catch (\Throwable $e) {
            Log::error('Gagal menambahkan data mustahik zakat fitrah', [
                'error' => $e->getMessage(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Data mustahik gagal disimpan. Silakan coba kembali.');
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
