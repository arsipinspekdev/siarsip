<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\SuratMasukExport;
use App\Http\Requests\SuratMasuk\StoreSuratMasukRequest;
use App\Http\Requests\SuratMasuk\UpdateSuratMasukRequest;
use App\Models\SuratMasuk;
use App\Traits\ConvertsImageToPdf;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class SuratMasukController extends Controller
{
    use ConvertsImageToPdf;

    /**
     * Display a listing of Surat Masuk with search, sorting, and pagination.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $sort = $request->query('sort', 'tanggal_terima');
        $direction = $request->query('direction', 'desc');

        // Whitelist allowed sort columns
        $allowedSorts = ['id', 'nomor_surat', 'tanggal_surat', 'tanggal_terima', 'asal_surat'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'tanggal_terima';
        }
        if (!in_array(strtolower($direction), ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $query = SuratMasuk::with('diterimaOleh');

        // Full search filter
        if (!empty($search)) {
            $term = trim($search);
            $query->where(function ($q) use ($term) {
                $q->where('nomor_surat', 'like', "%{$term}%")
                  ->orWhere('asal_surat', 'like', "%{$term}%")
                  ->orWhere('perihal', 'like', "%{$term}%");
            });
        }

        $suratMasuk = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();

        return view('surat-masuk.index', compact('suratMasuk', 'search', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new Surat Masuk.
     */
    public function create(): View
    {
        return view('surat-masuk.create');
    }

    /**
     * Store a newly created Surat Masuk in database.
     */
    public function store(StoreSuratMasukRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['diterima_oleh_id'] = Auth::id();

        // Handle File Upload secara aman (menyimpan file asli dan versi PDF jika gambar)
        if ($request->hasFile('file_surat') && $request->file('file_surat')->isValid()) {
            $files = $this->handleDocumentUpload($request->file('file_surat'), 'surat_masuk');
            $validated['file_surat'] = $files['file_surat'];
            $validated['file_pdf']   = $files['file_pdf'];
        }

        $surat = SuratMasuk::create($validated);

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', "Surat Masuk No. Agenda {$surat->no_agenda_formatted} berhasil ditambahkan ke arsip.");
    }

    /**
     * Display the specified Surat Masuk detail.
     */
    public function show(SuratMasuk $suratMasuk): View
    {
        $suratMasuk->load('diterimaOleh');

        return view('surat-masuk.show', compact('suratMasuk'));
    }

    /**
     * Show the form for editing the specified Surat Masuk.
     */
    public function edit(SuratMasuk $suratMasuk): View
    {
        return view('surat-masuk.edit', compact('suratMasuk'));
    }

    /**
     * Update the specified Surat Masuk in database.
     */
    public function update(UpdateSuratMasukRequest $request, SuratMasuk $suratMasuk): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('file_surat') && $request->file('file_surat')->isValid()) {
            $this->deleteOldDocuments($suratMasuk->file_surat, $suratMasuk->file_pdf);

            $files = $this->handleDocumentUpload($request->file('file_surat'), 'surat_masuk');
            $validated['file_surat'] = $files['file_surat'];
            $validated['file_pdf']   = $files['file_pdf'];
        }

        $suratMasuk->update($validated);

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', "Perubahan data Surat Masuk No. Agenda {$suratMasuk->no_agenda_formatted} berhasil disimpan.");
    }

    /**
     * Remove the specified Surat Masuk from database (Soft Delete).
     */
    public function destroy(SuratMasuk $suratMasuk): RedirectResponse
    {
        $agenda = $suratMasuk->no_agenda_formatted;
        $suratMasuk->delete();

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', "Surat Masuk No. Agenda {$agenda} berhasil dipindahkan ke tempat sampah.");
    }

    /**
     * Bulk destroy selected Surat Masuk items.
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Tidak ada data surat yang dipilih.');
        }

        $count = SuratMasuk::whereIn('id', $ids)->delete();

        return redirect()
            ->route('surat-masuk.index')
            ->with('success', "Sebanyak {$count} data surat masuk berhasil dihapus.");
    }

    /**
     * Download attachment file securely.
     *
     * Query params:
     *   type: 'pdf' (default) | 'original'
     *   name: 'agenda' (default, e.g. "Surat_Masuk_No_1_PDF.pdf") | 'original' (gunakan nama file asli upload)
     */
    public function download(Request $request, SuratMasuk $suratMasuk): BinaryFileResponse|RedirectResponse
    {
        $type    = $request->query('type', 'pdf');
        $namePref = $request->query('name', 'agenda');

        if ($type === 'pdf') {
            $targetFile = $suratMasuk->file_pdf ?: $suratMasuk->file_surat;
        } else {
            $targetFile = $suratMasuk->file_surat ?: $suratMasuk->file_pdf;
        }

        if (!$targetFile || !Storage::disk('public')->exists($targetFile)) {
            return back()->with('error', 'File lampiran tidak ditemukan di server.');
        }

        $path      = Storage::disk('public')->path($targetFile);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // Tentukan nama file download
        if ($namePref === 'original' && !empty($suratMasuk->original_file_name)) {
            // Gunakan nama file asli yang diupload (minus path prefix, preserve extension)
            $downloadName = pathinfo($suratMasuk->original_file_name, PATHINFO_FILENAME)
                . '.' . $extension;
        } else {
            // Default: nama deskriptif berdasarkan no_agenda
            $noAgenda    = $suratMasuk->no_agenda ?? $suratMasuk->id;
            $suffix      = $type === 'pdf' ? 'PDF' : 'Asli';
            $downloadName = 'Surat_Masuk_No_' . $noAgenda . '_' . $suffix . '.' . $extension;
        }

        return response()->download($path, $downloadName);
    }


    /**
     * Export report as PDF.
     */
    public function exportPdf(Request $request)
    {
        $search = $request->query('q');
        $query = SuratMasuk::with('diterimaOleh')->orderBy('no_agenda', 'asc');

        if ($search) {
            $term = $search;
            $query->where(function ($q) use ($term) {
                $q->where('nomor_surat', 'like', "%{$term}%")
                  ->orWhere('asal_surat', 'like', "%{$term}%")
                  ->orWhere('perihal', 'like', "%{$term}%");
            });
        }

        $items = $query->get();
        $pdf = Pdf::loadView('surat-masuk.report-pdf', compact('items', 'search'))->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Surat_Masuk_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Export report as Excel (.xlsx).
     */
    public function exportExcel(Request $request)
    {
        $search = $request->query('q');
        return Excel::download(new SuratMasukExport($search), 'Laporan_Surat_Masuk_' . date('Ymd_His') . '.xlsx');
    }

    /**
     * Export report as CSV.
     */
    public function exportCsv(Request $request)
    {
        $search = $request->query('q');
        return Excel::download(new SuratMasukExport($search), 'Laporan_Surat_Masuk_' . date('Ymd_His') . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * Print View (clean table for paper print).
     */
    public function print(Request $request): View
    {
        $search = $request->query('q');
        $query = SuratMasuk::with('diterimaOleh')->orderBy('no_agenda', 'asc');

        if ($search) {
            $term = $search;
            $query->where(function ($q) use ($term) {
                $q->where('nomor_surat', 'like', "%{$term}%")
                  ->orWhere('asal_surat', 'like', "%{$term}%")
                  ->orWhere('perihal', 'like', "%{$term}%");
            });
        }

        $items = $query->get();

        return view('surat-masuk.print', compact('items', 'search'));
    }
}
