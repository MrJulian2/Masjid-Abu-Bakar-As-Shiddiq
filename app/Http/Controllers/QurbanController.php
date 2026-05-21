<?php

namespace App\Http\Controllers;

use App\Models\kuponqurban;
use App\Models\qurban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Barryvdh\DomPDF\Facade\Pdf;

class QurbanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function scanPage()
    {
        return view('admin.Qurban.scan');
    }
    public function scanStore(Request $request)
    {
        $request->validate([
            'qr_code' => 'required',
        ]);

        $kupon = kuponqurban::where('qr_code', $request->qr_code)->first();

        if (!$kupon) {
            return response()->json([
                'status' => 'error',
                'message' => 'QR Code tidak valid!',
            ]);
        }

        if ($kupon->status == 'sudah_diambil') {
            return response()->json([
                'status' => 'warning',
                'message' => 'Kupon sudah diambil!',
            ]);
        }

        $kupon->update([
            'status' => 'sudah_diambil',
            'scanned_by' => auth()->id(),
            'scanned_at' => now(),
            'used_at' => now(),
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Kupon berhasil diambil',
            'nama' => $kupon->qurban->nama,
            'rt' => $kupon->qurban->rt,
            'rw' => $kupon->qurban->rw,
            'qr_code' => $kupon->qr_code,
        ]);
    }

    public function kuponIndex()
    {
        $qurban = Qurban::with([
            'kuponqurban' => function ($q) {
                $q->where('status', 'belum_diambil');
            },
        ])
            ->orderBy('rw')
            ->orderBy('rt')
            ->get();
        return view('admin.Qurban.kupon', compact('qurban'));
    }

    public function exportPdf()
    {
        $qurban = Qurban::with('kuponqurban')->orderBy('rw')->orderBy('rt')->get();

        // 🔥 REKAP RW / RT
        $rwStats = \App\Models\KuponQurban::select('qurbans.rw', 'qurbans.rt', DB::raw('COUNT(*) as total'), DB::raw("SUM(CASE WHEN kuponqurbans.status = 'sudah_diambil' THEN 1 ELSE 0 END) as sudah"), DB::raw("SUM(CASE WHEN kuponqurbans.status = 'belum_diambil' THEN 1 ELSE 0 END) as belum"))->join('qurbans', 'qurbans.id', '=', 'kuponqurbans.qurban_id')->groupBy('qurbans.rw', 'qurbans.rt')->orderBy('qurbans.rw')->orderBy('qurbans.rt')->get()->groupBy('rw');

        $pdf = Pdf::loadView('admin.Qurban.laporan-pdf', compact('qurban', 'rwStats'))->setPaper('a4', 'portrait');

        return $pdf->download('laporan-qurban.pdf');
    }
    public function index(Request $request)
    {
        // ==================================================
        // 1. DATA QURBAN (FILTER + SORT)
        // ==================================================
        $qurban = Qurban::with(['kuponqurban', 'user'])
            ->when($request->rw, fn($q) => $q->where('rw', $request->rw))
            ->when($request->rt, fn($q) => $q->where('rt', $request->rt))
            ->orderByRaw('CAST(rw AS UNSIGNED) ASC')
            ->orderByRaw('CAST(rt AS UNSIGNED) ASC')
            ->get();

        // ==================================================
        // 2. STATUS QURBAN (IN MEMORY - NO QUERY LAGI)
        // ==================================================
        foreach ($qurban as $row) {
            $total = $row->kuponqurban->count();
            $sudah = $row->kuponqurban->where('status', 'sudah_diambil')->count();

            $row->status = match (true) {
                $total === 0 => 'Belum Diambil',
                $sudah === 0 => 'Belum Diambil',
                $sudah < $total => 'Sebagian Diambil',
                default => 'Sudah Diambil',
            };

            $row->updated_by_name = optional(\App\Models\User::find($row->updated_by))->name ?? '-';
        }

        // ==================================================
        // 3. STATISTIK RW / RT (OPTIMIZED QUERY)
        // ==================================================
        $rwStats = KuponQurban::select('qurbans.rw', 'qurbans.rt', DB::raw('COUNT(*) as total'), DB::raw("SUM(CASE WHEN kuponqurbans.status = 'sudah_diambil' THEN 1 ELSE 0 END) as sudah"), DB::raw("SUM(CASE WHEN kuponqurbans.status = 'belum_diambil' THEN 1 ELSE 0 END) as belum"))->join('qurbans', 'qurbans.id', '=', 'kuponqurbans.qurban_id')->groupBy('qurbans.rw', 'qurbans.rt')->get()->sortBy(fn($item) => (int) $item->rw)->groupBy('rw')->map(fn($items) => $items->sortBy(fn($i) => (int) $i->rt));

        // ==================================================
        // 4. RETURN VIEW
        // ==================================================
        return view('admin.Qurban.index', [
            'qurban' => $qurban,
            'rwGrouped' => $rwStats,
        ]);
    }

    public function add()
    {
        return view('admin.Qurban.tambah');
    }

    public function store(Request $request)
    {
        // 1. VALIDASI
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'rt' => 'required|integer|min:1|max:255',
                'rw' => 'required|integer|min:1|max:255',
                'jumlah_kupon' => 'required|integer|min:1',
                'no_hp' => ['nullable', 'regex:/^0[0-9]{8,19}$/'],
            ],
            [
                'nama.required' => 'Nama penerima wajib diisi.',
                'rt.required' => 'RT wajib diisi.',
                'rw.required' => 'RW wajib diisi.',
                'rt.integer' => 'RT harus berupa angka tidak boleh ada 0 di depan.',
                'rw.integer' => 'RW harus berupa angka tidak boleh ada 0 di depan.',
                'jumlah_kupon.required' => 'Jumlah kupon wajib diisi.',
                'jumlah_kupon.integer' => 'Jumlah kupon harus berupa angka.',
                'jumlah_kupon.min' => 'Jumlah kupon minimal 1.',
                'no_hp.regex' => 'Nomor HP tidak valid. Harus diawali dengan 0 dan ex: 081234567890.',
            ],
        );

        if ($validator->fails()) {
            Alert::error('Validasi Gagal', $validator->errors()->first());
            return back()->withInput();
        }

        try {
            DB::beginTransaction();

            $user_id = auth()->id();

            // 2. SIMPAN QURBAN
            $qurban = qurban::create([
                'nama' => $request->nama,
                'nomor_hp' => $request->nomor_hp ?? '',
                'alamat' => $request->alamat ?? '',
                'rt' => $request->rt,
                'rw' => $request->rw,
                'jumlah_kupon' => $request->jumlah_kupon,
                'created_by' => $user_id,
            ]);

            // 3. BUAT KUpon
            for ($i = 0; $i < $request->jumlah_kupon; $i++) {
                $qrCode = 'QURBAN-' . $qurban->id . '-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT);

                $qurban->kuponqurban()->create([
                    'qurbans_id' => $qurban->id,
                    'qr_code' => $qrCode,
                    'status' => 'belum_diambil',
                    'scanned_at' => null,
                ]);
            }

            DB::commit();

            Alert::success('Berhasil', 'Penerima Qurban berhasil ditambahkan!');

            return redirect()->route('qurban.add');
        } catch (\Exception $e) {
            DB::rollBack();

            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());

            return back()->withInput();
        }
    }
    public function edit($id)
    {
        $qurban = qurban::findOrFail($id);
        // dd($qurban->id);

        $kupon = kuponqurban::where('qurban_id', $qurban->id)->get();
        // dd($kupon->toArray());
        // dd($kupon->toArray());
        return view('admin.Qurban.edit', compact('qurban', 'kupon'));
    }
    public function update(Request $request, $id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengedit data qurban');
        }
        // 1. VALIDASI
        $validator = Validator::make(
            $request->all(),
            [
                'nama' => 'required',
                'rt' => 'required|integer|min:1|max:255',
                'rw' => 'required|integer|min:1|max:255',
                'jumlah_kupon' => 'required|integer|min:1',
                'nomor_hp' => ['nullable', 'regex:/^0[0-9]{8,19}$/'],
            ],
            [
                'nama.required' => 'Nama penerima wajib diisi.',
                'rt.required' => 'RT wajib diisi.',
                'rw.required' => 'RW wajib diisi.',
                'rt.integer' => 'RT harus berupa angka.',
                'rw.integer' => 'RW harus berupa angka.',
                'jumlah_kupon.required' => 'Jumlah kupon wajib diisi.',
                'jumlah_kupon.integer' => 'Jumlah kupon harus berupa angka.',
                'jumlah_kupon.min' => 'Jumlah kupon minimal 1.',
                'nomor_hp.regex' => 'Nomor HP tidak valid. Harus diawali 0 (contoh: 08123456789).',
            ],
        );

        // dd($validator);

        if ($validator->fails()) {
            Alert::error('Validasi Gagal', $validator->errors()->first());
            return back()->withInput();
        }

        DB::beginTransaction();

        try {
            // 2. AMBIL DATA QURBAN
            $qurban = Qurban::findOrFail($id);

            // 3. UPDATE DATA QURBAN
            $qurban->update([
                'nama' => $request->nama,
                'nomor_hp' => $request->nomor_hp ?? '',
                'alamat' => $request->alamat ?? '',
                'rt' => $request->rt,
                'rw' => $request->rw,
            ]);

            $jumlahBaru = (int) $request->jumlah_kupon;

            $kuponLama = KuponQurban::where('qurban_id', $id)->count();

            /*
        | TAMBAH KUPON
        */
            if ($jumlahBaru > $kuponLama) {
                $selisih = $jumlahBaru - $kuponLama;

                for ($i = 0; $i < $selisih; $i++) {
                    KuponQurban::create([
                        'qurban_id' => $qurban->id,
                        'qr_code' => 'QURBAN-' . $qurban->id . '-' . str_pad($kuponLama + $i + 1, 4, '0', STR_PAD_LEFT),
                        'status' => 'belum_diambil',
                    ]);
                }
            } /*
        | HAPUS KUPON
        */ elseif ($jumlahBaru < $kuponLama) {
                $selisih = $kuponLama - $jumlahBaru;

                $kuponHapus = KuponQurban::where('qurban_id', $id)->orderByDesc('id')->take($selisih)->get();

                foreach ($kuponHapus as $k) {
                    $k->delete();
                }
            }

            // 4. UPDATE JUMLAH
            $qurban->update([
                'jumlah_kupon' => $jumlahBaru,
                'updated_by' => auth()->id(),
            ]);

            DB::commit();

            Alert::success('Berhasil', 'Data berhasil diupdate!');
            return redirect()->route('qurban.index');
        } catch (\Exception $e) {
            DB::rollBack();

            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return back()->withInput();
        }
    }
    public function destroy($id)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Hanya admin yang dapat mengedit data qurban');
        }
        try {
            $qurban = qurban::findOrFail($id);
            $qurban->delete();

            Alert::success('Berhasil', 'Data berhasil dihapus!');
            return redirect()->route('qurban.index');
        } catch (\Exception $e) {
            Alert::error('Error', 'Terjadi kesalahan: ' . $e->getMessage());
            return back();
        }
    }

    public function validasiManual()
    {
        $qurban = Qurban::with('kuponqurban')->get();

        return view('admin.Qurban.validasi-manual', compact('qurban'));
    }
    public function validasiManualProcess($id)
    {
        $kupon = KuponQurban::findOrFail($id);

        // cegah double ambil
        if ($kupon->status == 'sudah_diambil') {
            return back()->with('error', 'Kupon sudah divalidasi');
        }

        // buld validasi apabila kupon > 1
        foreach ($kupon->qurban->kuponqurban as $k) {
            if ($k->status == 'belum_diambil') {
                $k->update([
                    'status' => 'sudah_diambil',
                    'scanned_by' => auth()->id(),
                    'scanned_at' => now(),
                    'note' => 'Validasi manual tanpa kupon',
                ]);
            }
        }

        return back()->with('success', 'Validasi manual berhasil');
    }
}
