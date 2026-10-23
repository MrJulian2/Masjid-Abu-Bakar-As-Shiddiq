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
            $user = auth()->user();
            $muzakki = muzakki::create([
                'periode_id' => $periodeAktif->id,
                'kategori' => $request->kategori,
                'alamat' => $request->alamat,
                'created_by' => $user->id,
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

    public function kelolamuzakki()
    {
        $muzakkis = Muzakki::with('details')->latest()->get();

        return view(
            'admin.Zakat.muzakki-kelola',
            compact('muzakkis')
        );
    }

    public function anggota($id)
    {
        $muzakki = Muzakki::with('details')->findOrFail($id);
        // dd($muzakki);
        return view('admin.Zakat.muzakki-anggota', compact('muzakki'));
    }

    public function show(string $id)
    {
        // $muzakki = Muzakki::with('details')->findOrFail($id);

        // return view('admin.Zakat.muzakki-detail', compact('muzakki'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $muzakki = Muzakki::with('details')->findOrFail($id);

        $opsiBeras = ZakatOpsi::where('jenis', 'beras')
            ->where('aktif', true)
            ->get();

        $opsiUang = ZakatOpsi::where('jenis', 'uang')
            ->where('aktif', true)
            ->get();

        return view('admin.Zakat.muzakki-edit', compact(
            'muzakki',
            'opsiBeras',
            'opsiUang'
        ));
    }

    /**
     * Update the specified resource in storage.
     */


    public function update(Request $request, $id)
    {
        $muzakki = Muzakki::with('details')->findOrFail($id);

        $validated = $request->validate([
            'kategori'       => 'required|in:setempat,luar',
            'alamat'         => 'required|string|max:255',
            'rt'             => 'nullable|required_if:kategori,setempat|numeric',
            'rw'             => 'nullable|required_if:kategori,setempat|numeric',

            'nama'           => 'required|array|min:1',
            'nama.*'         => 'required|string|max:255',

            'detail_id'      => 'nullable|array',
            'detail_id.*'    => 'nullable|integer',

            'jenis_zakat'    => 'required|array|min:1',
            'jenis_zakat.*'  => 'required|in:beras,uang',

            'berat_beras'    => 'required|array',
            'berat_beras.*'  => 'nullable|numeric|min:0',

            'nominal_uang'   => 'required|array',
            'nominal_uang.*' => 'nullable|numeric|min:0',
        ]);

        // Pastikan seluruh array memiliki jumlah baris yang sama.
        $jumlahAnggota = count($validated['nama']);

        foreach (['jenis_zakat', 'berat_beras', 'nominal_uang'] as $field) {
            if (count($validated[$field]) !== $jumlahAnggota) {
                return back()
                    ->withInput()
                    ->withErrors([
                        $field => 'Data anggota tidak sinkron. Silakan periksa kembali form.',
                    ]);
            }
        }

        DB::transaction(function () use ($validated, $muzakki) {

            // 1. Update data utama Muzakki.
            $user = auth()->user();
            $muzakki->update([
                'kategori' => $validated['kategori'],
                'alamat'   => $validated['alamat'],
                'rt'       => $validated['kategori'] === 'setempat'
                    ? $validated['rt']
                    : null,
                'rw'       => $validated['kategori'] === 'setempat'
                    ? $validated['rw']
                    : null,
                'updated_by' => $user->id,
            ]);

            $detailIdsTersimpan = [];

            // 2. Update anggota lama berdasarkan ID atau insert anggota baru.
            foreach ($validated['nama'] as $index => $nama) {

                $jenis = $validated['jenis_zakat'][$index];

                $beratBeras = $jenis === 'beras'
                    ? ($validated['berat_beras'][$index] ?? null)
                    : null;

                $nominalUang = $jenis === 'uang'
                    ? ($validated['nominal_uang'][$index] ?? null)
                    : null;

                // Pastikan nilai untuk jenis zakat yang dipilih tersedia.
                if ($jenis === 'beras' && $beratBeras === null) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "berat_beras.$index" => 'Berat beras wajib dipilih.',
                    ]);
                }

                if ($jenis === 'uang' && $nominalUang === null) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        "nominal_uang.$index" => 'Nominal uang wajib dipilih.',
                    ]);
                }

                $dataDetail = [
                    'nama'         => $nama,
                    'jenis_zakat'  => $jenis,
                    'berat_beras'  => $beratBeras,
                    'nominal_uang' => $nominalUang,
                ];

                $detailId = $validated['detail_id'][$index] ?? null;

                if (!empty($detailId)) {

                    // Cari hanya dalam relasi Muzakki ini.
                    $detail = $muzakki->details()
                        ->whereKey($detailId)
                        ->first();

                    // Tolak ID yang bukan milik Muzakki ini.
                    if (!$detail) {
                        throw \Illuminate\Validation\ValidationException::withMessages([
                            'detail_id' => 'Data anggota tidak valid. Silakan muat ulang halaman.',
                        ]);
                    }

                    // Update anggota lama berdasarkan ID.
                    $detail->update($dataDetail);
                } else {

                    // Insert anggota baru.
                    $detail = $muzakki->details()->create($dataDetail);
                }

                $detailIdsTersimpan[] = $detail->id;
            }

            // 3. Hapus anggota lama yang memang tidak dikirim dari form.
            $muzakki->details()
                ->whereNotIn('id', $detailIdsTersimpan)
                ->delete();
        });

        Alert::success('Berhasil', 'Data Muzakki berhasil diperbarui');

        return redirect()->route('zakat-muzakki.kelola');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            // Cari data Muzakki beserta detail anggotanya.
            $muzakki = Muzakki::with('details')
                ->lockForUpdate()
                ->findOrFail($id);

            // Hapus seluruh detail anggota terlebih dahulu.
            $muzakki->details()->delete();

            // Hapus data utama Muzakki.
            $muzakki->delete();

            // Simpan perubahan jika semua proses berhasil.
            DB::commit();

            Alert::success(
                'Berhasil',
                'Data Muzakki beserta seluruh detail anggotanya berhasil dihapus.'
            );

            return redirect()->route('zakat-muzakki.kelola');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Batalkan transaksi jika data tidak ditemukan.
            DB::rollBack();

            Alert::error(
                'Gagal',
                'Data Muzakki tidak ditemukan.'
            );

            return redirect()->route('zakat-muzakki.kelola');
        } catch (\Illuminate\Database\QueryException $e) {
            // Batalkan transaksi jika terjadi kesalahan database.
            DB::rollBack();

            Alert::error(
                'Gagal',
                'Data Muzakki tidak dapat dihapus karena masih memiliki data terkait.'
            );

            return redirect()->route('zakat-muzakki.kelola');
        } catch (\Exception $e) {
            // Batalkan transaksi jika terjadi kesalahan lainnya.
            DB::rollBack();

            Alert::error(
                'Gagal',
                'Terjadi kesalahan saat menghapus data Muzakki.'
            );

            return redirect()->route('zakat-muzakki.kelola');
        }
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
