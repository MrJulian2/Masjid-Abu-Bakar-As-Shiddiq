<?php

namespace App\Http\Controllers\Admin\Zakat;

use App\Http\Controllers\Controller;
use App\Models\KuponMustahik;
use App\Models\Mustahik;
use App\Models\ZakatPeriode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Str;

class MustahikController extends Controller
{



    public function kelolamustahik(Request $request)
    {
        // Periode zakat aktif
        $periodeAktif = ZakatPeriode::where('aktif', 1)->first();

        // Query data berdasarkan periode aktif
        $queryDasar = Mustahik::query()
            ->when(
                $periodeAktif,
                fn($query) => $query->where('periode_id', $periodeAktif->id),
                fn($query) => $query->whereRaw('1 = 0')
            );

        // Ambil semua data untuk statistik dan distribusi
        $semuaMustahik = (clone $queryDasar)
            ->with('kuponmustahik')
            ->get();

        // Status mustahik mengikuti status kuponnya.
        // Jika setidaknya satu kupon sudah diambil, dihitung sudah.
        $statusSudahDiambil = function ($mustahik) {
            return $mustahik->kuponmustahik->contains(
                fn($kupon) => $kupon->status === 'sudah_diambil'
            );
        };

        // Statistik
        $totalMustahik = $semuaMustahik->count();

        $sudahDisalurkan = $semuaMustahik
            ->filter($statusSudahDiambil)
            ->count();

        $belumDisalurkan = $totalMustahik - $sudahDisalurkan;

        $totalWilayah = $semuaMustahik
            ->filter(fn($m) => filled($m->rt) && filled($m->rw))
            ->map(fn($m) => $m->rt . '-' . $m->rw)
            ->unique()
            ->count();

        // Distribusi per RW dan RT
        $rwGrouped = $semuaMustahik
            ->filter(fn($m) => filled($m->rw))
            ->groupBy('rw')
            ->sortKeys()
            ->map(function ($dataRW) use ($statusSudahDiambil) {
                $rts = $dataRW
                    ->filter(fn($m) => filled($m->rt))
                    ->groupBy('rt')
                    ->sortKeys()
                    ->map(function ($dataRT) use ($statusSudahDiambil) {
                        $sudah = $dataRT->filter($statusSudahDiambil)->count();

                        return [
                            'total' => $dataRT->count(),
                            'sudah' => $sudah,
                            'belum' => $dataRT->count() - $sudah,
                        ];
                    });

                return [
                    'total' => $dataRW->count(),
                    'rts' => $rts,
                ];
            });

        // Pilihan filter RT dan RW
        $daftarRT = $semuaMustahik
            ->pluck('rt')
            ->filter(fn($rt) => filled($rt))
            ->unique()
            ->sort()
            ->values();

        $daftarRW = $semuaMustahik
            ->pluck('rw')
            ->filter(fn($rw) => filled($rw))
            ->unique()
            ->sort()
            ->values();

        // Query tabel
        $query = (clone $queryDasar)
            ->with('kuponmustahik');

        if ($request->filled('nama')) {
            $query->where('nama', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('rt')) {
            $query->where('rt', $request->rt);
        }

        if ($request->filled('rw')) {
            $query->where('rw', $request->rw);
        }

        if ($request->status === 'sudah') {
            $query->whereHas('kuponmustahik', function ($q) {
                $q->where('status', 'sudah_diambil');
            });
        } elseif ($request->status === 'belum') {
            $query->whereDoesntHave('kuponmustahik', function ($q) {
                $q->where('status', 'sudah_diambil');
            });
        }

        $mustahiks = $query
            ->orderBy('nama')
            ->get();

        return view('admin.Zakat.mustahik-kelola', compact(
            'periodeAktif',
            'mustahiks',
            'totalMustahik',
            'sudahDisalurkan',
            'belumDisalurkan',
            'totalWilayah',
            'rwGrouped',
            'daftarRT',
            'daftarRW'
        ));
    }


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

        DB::beginTransaction();

        try {
            // Pastikan periode masih aktif.
            $periodeAktif = ZakatPeriode::where('id', $request->periode_id)
                ->where('aktif', 1)
                ->lockForUpdate()
                ->first();

            if (!$periodeAktif) {
                DB::rollBack();

                return back()
                    ->withInput()
                    ->with('error', 'Periode zakat tidak aktif atau sudah berubah.');
            }

            $user = auth()->user();

            // Simpan data mustahik.
            $mustahik = Mustahik::create([
                'periode_id' => $periodeAktif->id,
                'nama'       => $request->nama,
                'nomor_hp'   => $request->nomor_hp,
                'alamat'     => $request->alamat,
                'rt'         => $request->rt,
                'rw'         => $request->rw,
                'created_by' => $user->id,
            ]);

            // Buat kode QR unik untuk kupon mustahik.
            $qrCode = 'ZAKAT-' . $periodeAktif->id
                . '-' . $mustahik->id
                . '-' . Str::upper(Str::random(12));

            // Buat kupon secara otomatis.
            KuponMustahik::create([
                'mustahik_id' => $mustahik->id,
                'qr_code'     => $qrCode,
                'status'      => 'belum_diambil',
            ]);

            // Simpan kedua data jika seluruh proses berhasil.
            DB::commit();

            Alert::success(
                'Berhasil',
                'Data mustahik dan kupon zakat berhasil dibuat.'
            );

            return redirect()->route('zakat-mustahik.create');
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Gagal menambahkan mustahik dan kupon zakat', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Data mustahik dan kupon gagal disimpan. Silakan coba kembali.');
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
    public function edit($id)
    {
        $mustahik = Mustahik::with('periode')->findOrFail($id);

        $periodeAktif = ZakatPeriode::where('aktif', true)->first();

        return view('admin.Zakat.mustahik-edit', compact(
            'mustahik',
            'periodeAktif'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $mustahik = Mustahik::findOrFail($id);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'alamat' => ['required', 'string', 'max:255'],
            'rt' => ['nullable', 'string', 'max:10'],
            'rw' => ['nullable', 'string', 'max:10'],
            'nomor_hp' => ['nullable', 'string', 'max:20'],
        ], [
            'nama.required' => 'Nama mustahik wajib diisi.',
            'alamat.required' => 'Alamat mustahik wajib diisi.',
            'nama.max' => 'Nama mustahik maksimal 255 karakter.',
            'alamat.max' => 'Alamat maksimal 255 karakter.',
        ]);

        DB::beginTransaction();

        $user = auth()->user();
        try {
            $mustahik->nama = $validated['nama'];
            $mustahik->alamat = $validated['alamat'];
            $mustahik->rt = $validated['rt'] ?? null;
            $mustahik->rw = $validated['rw'] ?? null;
            $mustahik->nomor_hp = $validated['nomor_hp'] ?? null;
            $mustahik->updated_by = $user->id;

            $mustahik->save();

            DB::commit();

            Alert::success('Berhasil', 'Data mustahik berhasil diperbarui.');
            return redirect()
                ->route('zakat-mustahik.kelola');
        } catch (\Throwable $e) {
            DB::rollBack();

            report($e);

            Alert::error('Gagal', 'Terjadi kesalahan saat memperbarui data mustahik.');
            return back()
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    public function halamanCetakKupon(Request $request)

    {
        // Periode zakat aktif
        $periodeAktif = ZakatPeriode::where('aktif', 1)->first();

        // Default data kosong jika tidak ada periode aktif
        $kupons = collect();

        if ($periodeAktif) {
            $kupons = KuponMustahik::with('mustahik')
                ->where('status', 'belum_diambil')

                // Hanya kupon milik Mustahik pada periode aktif
                ->whereHas('mustahik', function ($q) use ($periodeAktif) {
                    $q->where('periode_id', $periodeAktif->id);
                })

                // Filter nama Mustahik
                ->when($request->filled('nama'), function ($q) use ($request) {
                    $q->whereHas('mustahik', function ($mustahik) use ($request) {
                        $mustahik->where(
                            'nama',
                            'like',
                            '%' . $request->nama . '%'
                        );
                    });
                })

                // Filter RW
                ->when($request->filled('rw'), function ($q) use ($request) {
                    $q->whereHas('mustahik', function ($mustahik) use ($request) {
                        $mustahik->where('rw', $request->rw);
                    });
                })

                // Filter RT
                ->when($request->filled('rt'), function ($q) use ($request) {
                    $q->whereHas('mustahik', function ($mustahik) use ($request) {
                        $mustahik->where('rt', $request->rt);
                    });
                })

                // Urutkan berdasarkan RW kemudian RT
                ->orderByRaw("
                    (
                        SELECT CAST(rw AS UNSIGNED)
                        FROM mustahiks
                        WHERE mustahiks.id = kuponmustahiks.mustahik_id
                    ) ASC
                ")
                ->orderByRaw("
                    (
                        SELECT CAST(rt AS UNSIGNED)
                        FROM mustahiks
                        WHERE mustahiks.id = kuponmustahiks.mustahik_id
                    ) ASC
                ")
                ->orderBy('id')
                ->get();
        }

        return view('admin.Zakat.mustahik-kupon', [
            'kupons' => $kupons,
            'periodeAktif' => $periodeAktif,
            'autoPrint' => false,
        ]);
    }

    /**
     * Mencetak kupon Mustahik yang dipilih.
     */
    public function printSelected(Request $request)
    {
        // Validasi kupon yang dipilih
        $request->validate(
            [
                'selected_ids' => ['required', 'array', 'min:1'],
                'selected_ids.*' => [
                    'required',
                    'integer',
                    'distinct',
                ],
            ],
            [
                'selected_ids.required' => 'Pilih minimal satu kupon untuk dicetak.',
                'selected_ids.array' => 'Data kupon yang dipilih tidak valid.',
                'selected_ids.min' => 'Pilih minimal satu kupon untuk dicetak.',
                'selected_ids.*.required' => 'ID kupon tidak valid.',
                'selected_ids.*.integer' => 'ID kupon harus berupa angka.',
                'selected_ids.*.distinct' => 'Terdapat kupon yang dipilih lebih dari satu kali.',
            ]
        );

        // Periksa periode aktif
        $periodeAktif = ZakatPeriode::where('aktif', 1)->first();

        if (!$periodeAktif) {
            return redirect()
                ->route('zakat-mustahik.halamanCetakKupon')
                ->with(
                    'error',
                    'Belum ada periode zakat yang aktif. Silakan aktifkan periode terlebih dahulu.'
                );
        }

        // Ambil hanya kupon terpilih yang belum diambil
        // dan milik Mustahik pada periode aktif
        $selectedIds = $request->input('selected_ids');

        $kupons = KuponMustahik::with('mustahik')
            ->whereIn('id', $selectedIds)
            ->where('status', 'belum_diambil')
            ->whereHas('mustahik', function ($q) use ($periodeAktif) {
                $q->where('periode_id', $periodeAktif->id);
            })
            ->orderBy('id')
            ->get();

        // Pastikan seluruh kupon terpilih valid
        if ($kupons->count() !== count($selectedIds)) {
            return redirect()
                ->route('zakat-mustahik.halamanCetakKupon')
                ->with(
                    'error',
                    'Sebagian kupon tidak ditemukan, sudah diambil, atau bukan milik periode zakat aktif.'
                );
        }

        // Tampilkan halaman khusus cetak kupon terpilih
        return view('admin.Zakat.mustahik-kupon', [
            'kupons' => $kupons,
            'periodeAktif' => $periodeAktif,
            'autoPrint' => true,
        ]);
    }
}
