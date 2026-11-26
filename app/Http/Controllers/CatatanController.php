<?php

namespace App\Http\Controllers;

use App\Models\Catatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CatatanController extends Controller
{
   public function index(Request $request)
{
    $query = Catatan::where('user_id', auth()->id())
                    ->where('is_archived', false);

    if ($request->filled('cari')) {
        $keyword = '%' . $request->cari . '%';
        $query->where(function($q) use ($keyword) {
            $q->where('judul', 'like', $keyword)
              ->orWhere('isi', 'like', $keyword);
        });
    }

    $urut = $request->get('urut', 'terbaru');
    $query->orderBy('created_at', $urut == 'terlama' ? 'asc' : 'desc');

    $catatan = $query->paginate(5);
    $jumlah_aktif = Catatan::where('user_id', auth()->id())->where('is_archived', false)->count();

    return view('catatan.index', compact('catatan', 'jumlah_aktif'));
}
    public function create()
    {
        return view('catatan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,txt|max:2048',
        ]);

        $data = [
            'user_id' => auth()->id(),
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
        ];

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $this->uploadLampiran($request->file('lampiran'));
        }

        Catatan::create($data);

        return redirect('/catatan')->with('sukses', 'Catatan berhasil disimpan!');
    }

    public function show($id)
    {
        $catatan = $this->findUserCatatan($id);

        return view('catatan.show', compact('catatan'));
    }

    public function edit($id)
    {
        $catatan = $this->findUserCatatan($id);

        return view('catatan.edit', compact('catatan'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'isi' => 'required|string',
            'lampiran' => 'nullable|file|mimes:jpg,jpeg,png,pdf,txt|max:2048',
        ]);

        $catatan = $this->findUserCatatan($id);

        $data = [
            'judul' => $validated['judul'],
            'isi' => $validated['isi'],
        ];

        if ($request->hasFile('lampiran')) {
            $this->deleteLampiran($catatan->lampiran);
            $data['lampiran'] = $this->uploadLampiran($request->file('lampiran'));
        }

        $catatan->update($data);

        return redirect("/catatan/{$catatan->id}")->with('sukses', 'Catatan berhasil diperbarui!');
    }

   public function arsip(Request $request)
{
    $query = Catatan::where('user_id', auth()->id())
                    ->where('is_archived', true);

    if ($request->filled('cari')) {
        $keyword = '%' . $request->cari . '%';
        $query->where(function($q) use ($keyword) {
            $q->where('judul', 'like', $keyword)
              ->orWhere('isi', 'like', $keyword);
        });
    }

    $urut = $request->get('urut', 'terbaru');
    $query->orderBy('created_at', $urut == 'terlama' ? 'asc' : 'desc');

    $catatan = $query->paginate(5);
    $jumlah_arsip = Catatan::where('user_id', auth()->id())->where('is_archived', true)->count();

    return view('catatan.arsip', compact('catatan', 'jumlah_arsip'));
}

    public function destroy($id)
    {
        $catatan = $this->findUserCatatan($id);

        $this->deleteLampiran($catatan->lampiran);
        $catatan->delete();

        return redirect('/catatan')->with('sukses', 'Catatan berhasil dihapus.');
    }

    public function archive($id)
    {
        $catatan = $this->findUserCatatan($id);
        $catatan->update(['is_archived' => true]);

        return redirect('/catatan')->with('sukses', 'Catatan telah diarsipkan.');
    }

    public function unarchive($id)
    {
        $catatan = $this->findUserCatatan($id);
        $catatan->update(['is_archived' => false]);

        return redirect('/catatan')->with('sukses', 'Catatan dipulihkan dari arsip.');
    }

    private function findUserCatatan($id): Catatan
    {
        return Catatan::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
    }

    private function applySearchFilter($query, Request $request): void
    {
        if ($request->filled('cari')) {
            $keyword = '%' . $request->cari . '%';
            $query->where(function ($q) use ($keyword) {
                $q->where('judul', 'like', $keyword)
                    ->orWhere('isi', 'like', $keyword);
            });
        }
    }

    private function uploadLampiran($file): string
    {
        $filename = $file->hashName();
        $file->storeAs('lampiran', $filename, 'public');

        return 'lampiran/' . $filename;
    }

    private function deleteLampiran(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

public function export()
{
    $catatan = Catatan::where('user_id', auth()->id())
        ->where('is_archived', false)
        ->orderBy('created_at', 'desc')
        ->get();

    $filename = 'catatan_aktif_' . date('Y-m-d') . '.csv';

    $headers = [
        'Content-Type' => 'text/csv; charset=UTF-8',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    return response()->stream(function () use ($catatan) {
        $handle = fopen('php://output', 'w');

        // UTF-8 BOM agar karakter terbaca rapi di Excel
        fwrite($handle, "\xEF\xBB\xBF");

        // Pakai koma (default) sesuai setting Excel-mu
        fputcsv($handle, ['No', 'Judul', 'Isi', 'Tanggal Dibuat']);

        foreach ($catatan as $index => $item) {
            $judulBersih = preg_replace("/\r|\n/", ' ', (string) $item->judul);
            $isiBersih = preg_replace("/\r|\n/", ' ', (string) $item->isi);

            fputcsv($handle, [
                $index + 1,
                trim($judulBersih),
                trim($isiBersih),
                optional($item->created_at)->format('d/m/Y H:i'),
            ]);
        }

        fclose($handle);
    }, 200, $headers);
}
public function importForm()
{
    return view('catatan.import');
}
public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:csv,txt|max:2048'
    ]);

    $file = $request->file('file');
    $handle = fopen($file->getPathname(), 'r');

    // Lewati header
    fgetcsv($handle);

    $jumlah = 0;
    while (($row = fgetcsv($handle)) !== false) {
        if (count($row) >= 2) {
            Catatan::create([
                'user_id' => auth()->id(),
                'judul' => $row[0],
                'isi' => $row[1],
                'is_archived' => false,
                'created_at' => $row[2] ?? now(),
                'updated_at' => now()
            ]);
            $jumlah++;
        }
    }

    fclose($handle);

    return redirect('/catatan')->with('sukses', "Berhasil mengimpor {$jumlah} catatan.");
}
}