<?php

namespace App\Http\Controllers\Admin\Zakat;

use App\Http\Controllers\Controller;
use App\Models\muzakki;
use App\Models\muzakki_detail;
use App\Models\zakatopsi;
use App\Models\ZakatPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class ZakatMuzakkiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $opsiUang = zakatopsi::where('jenis', 'uang')
            ->whereHas('periode', function ($q) {
                $q->where('aktif', true);
            })
            ->where('aktif', true)
            ->get();

        $opsiBeras = zakatopsi::where('jenis', 'beras')
            ->whereHas('periode', function ($q) {
                $q->where('aktif', true);
            })
            ->where('aktif', true)
            ->get();

        return view('admin.Zakat.muzakki', compact('opsiUang', 'opsiBeras'));
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
        // dd($request->all());
        // ======================
        // VALIDASI
        // ======================
        $request->validate([
            'kategori' => 'required|in:setempat,luar',
            'alamat' => 'required|string',
            'rt' => 'nullable|numeric',
            'rw' => 'nullable|numeric',

            'nama' => 'required|array',
            'nama.*' => 'required|string',

            'jenis_zakat' => 'required|array',
            'jenis_zakat.*' => 'required|in:beras,uang',

            'berat_beras' => 'nullable|array',
            'nominal_uang' => 'nullable|array',
        ]);

        DB::beginTransaction();

        try {
            // ======================
            // SIMPAN HEADER MUZAKKI
            // ======================

            $periodeAktif = ZakatPeriode::where('aktif', true)->first();

            if (!$periodeAktif) {
                Alert::error('Gagal', 'Tidak ada periode zakat fitrah yang aktif. Silakan aktifkan periode terlebih dahulu.');
                return redirect()->back()->withInput();
            }

            $muzakki = muzakki::create([
                'periode_id' => $periodeAktif->id,
                'kategori' => $request->kategori,
                'alamat' => $request->alamat,
                'rt' => $request->kategori == 'luar' ? null : $request->rt,
                'rw' => $request->kategori == 'luar' ? null : $request->rw,
            ]);

            // ======================
            // SIMPAN DETAIL ANGGOTA
            // ======================
            $namaList = $request->nama;
            $jenisList = $request->jenis_zakat;
            $beratList = $request->berat_beras ?? [];
            $nominalList = $request->nominal_uang ?? [];
            $totalJiwa = 0;
            $totalBeras = 0;
            $totalUang = 0;
            foreach ($namaList as $i => $nama) {
                $jenis = $jenisList[$i] ?? 'beras';

                muzakki_detail::create([
                    'muzakki_id' => $muzakki->id,
                    'nama' => $nama,
                    'jenis_zakat' => $jenis,
                    'berat_beras' => $jenis == 'beras' ? $beratList[$i] ?? 2.5 : null,
                    'nominal_uang' => $jenis == 'uang' ? $nominalList[$i] ?? 50000 : null,
                ]);

                // hitung total beras dan uang
                if ($jenis == 'beras') {
                    $totalBeras += $beratList[$i] ?? 2.5;
                } else {
                    $totalUang += $nominalList[$i] ?? 50000;
                }

                $totalJiwa++;
            }

            // ======================
            // UPDATE TOTAL DI HEADER
            // ======================
            $muzakki->update([
                'total_jiwa' => $totalJiwa,
                'total_beras' => $totalBeras,
                'total_uang' => $totalUang,
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data muzakki berhasil disimpan');
            return redirect()->route('zakat-muzakki.index');
        } catch (\Exception $e) {
            DB::rollBack();

            Alert::error('Gagal', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
            return redirect()->back()->withInput();
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

    public function dashboard()
    {
        return view('admin.Zakat.dashboard', [
            'totalMuzakki' => 0,
            'totalJiwa' => 0,
            'totalBeras' => 0,
            'totalUang' => 0,
            'totalMustahik' => 0,
            'sudahValidasi' => 0,
            'belumValidasi' => 0,
        ]);
    }
}
