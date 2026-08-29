<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exports\SuratKeluarExport;
use App\Http\Requests\SuratKeluar\StoreSuratKeluarRequest;
use App\Http\Requests\SuratKeluar\UpdateSuratKeluarRequest;
use App\Models\SuratKeluar;
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

final class SuratKeluarController extends Controller
{
    use ConvertsImageToPdf;

    /**
     * Display a listing of Surat Keluar.
     */
    public function index(Request $request): View
    {
        $search = $request->query('q');
        $sort = $request->query('sort', 'tanggal_surat');
        $direction = $request->query('direction', 'desc');

        $allowedSorts = ['id', 'nomor_surat', 'tanggal_surat', 'tujuan_surat'];
        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'tanggal_surat';
        }
        if (!in_array(strtolower($direction), ['asc', 'desc'], true)) {
            $direction = 'desc';
        }

        $query = SuratKeluar::with('dibuatOleh');

        if (!empty($search)) {
            $term = trim($search);
            $query->where(function ($q) use ($term) {
                $q->where('nomor_surat', 'like', "%{$term}%")
                  ->orWhere('tujuan_surat', 'like', "%{$term}%")
                  ->orWhere('perihal', 'like', "%{$term}%");
            });
        }

        $suratKeluar = $query->orderBy($sort, $direction)->paginate(10)->withQueryString();

        return view('surat-keluar.index', compact('suratKeluar', 'search', 'sort', 'direction'));
    }

    /**
     * Show the form for creating a new Surat Keluar.
     */
    public function create(): View
    {
        return view('surat-keluar.create');
    }

    /**
     * Store a newly created Surat Keluar in database.
     */
    public function store(StoreSuratKeluarRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['dibuat_oleh_id'] = Auth::id();

        if ($request->hasFile('file_surat') && $request->file('file_surat')->isValid()) {
            $files = $this->handleDocumentUpload($request->file('file_surat'), 'surat_keluar');
            $validated['file_surat'] = $files['file_surat'];
            $validated['file_pdf']   = $files['file_pdf'];
        }

        $surat = SuratKeluar::create($validated);

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', "Surat Keluar No. Agenda {$surat->no_agenda_formatted} berhasil ditambahkan ke arsip.");
    }

    /**
     * Display the specified Surat Keluar detail.
     */
    public function show(SuratKeluar $suratKeluar): View
    {
        $suratKeluar->load('dibuatOleh');

        return view('surat-keluar.show', compact('suratKeluar'));
    }

    /**
     * Show the form for editing the specified Surat Keluar.
     */
    public function edit(SuratKeluar $suratKeluar): View
    {
        return view('surat-keluar.edit', compact('suratKeluar'));
    }

    /**
     * Update the specified Surat Keluar in database.
     */
    public function update(UpdateSuratKeluarRequest $request, SuratKeluar $suratKeluar): RedirectResponse
    {
        $validated = $request->validated();

        if ($request->hasFile('file_surat') && $request->file('file_surat')->isValid()) {
            $this->deleteOldDocuments($suratKeluar->file_surat, $suratKeluar->file_pdf);

            $files = $this->handleDocumentUpload($request->file('file_surat'), 'surat_keluar');
            $validated['file_surat'] = $files['file_surat'];
            $validated['file_pdf']   = $files['file_pdf'];
        }

        $suratKeluar->update($validated);

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', "Perubahan data Surat Keluar No. Agenda {$suratKeluar->no_agenda_formatted} berhasil disimpan.");
    }

    /**
     * Remove the specified Surat Keluar from database (Soft Delete).
     */
    public function destroy(SuratKeluar $suratKeluar): RedirectResponse
    {
        $agenda = $suratKeluar->no_agenda_formatted;
        $suratKeluar->delete();

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', "Surat Keluar No. Agenda {$agenda} berhasil dipindahkan ke tempat sampah.");
    }

    /**
     * Bulk destroy selected Surat Keluar items.
     */
    public function bulkDestroy(Request $request): RedirectResponse
    {
        $ids = $request->input('selected_ids', []);

        if (empty($ids) || !is_array($ids)) {
            return back()->with('error', 'Tidak ada data surat yang dipilih.');
        }

        $count = SuratKeluar::whereIn('id', $ids)->delete();

        return redirect()
            ->route('surat-keluar.index')
            ->with('success', "Sebanyak {$count} data surat keluar berhasil dihapus.");
    }

    /**
     * Download attachment file securely.
     *
     * Query params:
     *   type: 'pdf' (default) | 'original'
     *   name: 'agenda' (default, e.g. "Surat_Keluar_No_1_PDF.pdf") | 'original' (gunakan nama file asli upload)
     */
    public function download(Request $request, SuratKeluar $suratKeluar): BinaryFileResponse|RedirectResponse
    {
        $type     = $request->query('type', 'pdf');
        $namePref = $request->query('name', 'agenda');

        if ($type === 'pdf') {
            $targetFile = $suratKeluar->file_pdf ?: $suratKeluar->file_surat;
        } else {
            $targetFile = $suratKeluar->file_surat ?: $suratKeluar->file_pdf;
        }

        if (!$targetFile || !Storage::disk('public')->exists($targetFile)) {
            return back()->with('error', 'File lampiran tidak ditemukan di server.');
        }

        $path      = Storage::disk('public')->path($targetFile);
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        // Tentukan nama file download
        if ($namePref === 'original' && !empty($suratKeluar->original_file_name)) {
            $downloadName = pathinfo($suratKeluar->original_file_name, PATHINFO_FILENAME)
                . '.' . $extension;
        } else {
            $noAgenda     = $suratKeluar->no_agenda ?? $suratKeluar->id;
            $suffix       = $type === 'pdf' ? 'PDF' : 'Asli';
            $downloadName = 'Surat_Keluar_No_' . $noAgenda . '_' . $suffix . '.' . $extension;
        }

        return response()->download($path, $downloadName);
    }


    /**
     * Export report as PDF.
     */
    public function exportPdf(Request $request)
    {
        $search = $request->query('q');
        $query = SuratKeluar::with('dibuatOleh')->orderBy('no_agenda', 'asc');

        if ($search) {
            $term = $search;
            $query->where(function ($q) use ($term) {
                $q->where('nomor_surat', 'like', "%{$term}%")
                  ->orWhere('tujuan_surat', 'like', "%{$term}%")
                  ->orWhere('perihal', 'like', "%{$term}%");
            });
        }

        $items = $query->get();
        $pdf = Pdf::loadView('surat-keluar.report-pdf', compact('items', 'search'))->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_Surat_Keluar_' . date('Ymd_His') . '.pdf');
    }

    /**
     * Export report as Excel (.xlsx).
     */
    public function exportExcel(Request $request)
    {
        $search = $request->query('q');
        return Excel::download(new SuratKeluarExport($search), 'Laporan_Surat_Keluar_' . date('Ymd_His') . '.xlsx');
    }

    /**
     * Export report as CSV.
     */
    public function exportCsv(Request $request)
    {
        $search = $request->query('q');
        return Excel::download(new SuratKeluarExport($search), 'Laporan_Surat_Keluar_' . date('Ymd_His') . '.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * Print View (clean table for paper print).
     */
    public function print(Request $request): View
    {
        $search = $request->query('q');
        $query = SuratKeluar::with('dibuatOleh')->orderBy('no_agenda', 'asc');

        if ($search) {
            $term = $search;
            $query->where(function ($q) use ($term) {
                $q->where('nomor_surat', 'like', "%{$term}%")
                  ->orWhere('tujuan_surat', 'like', "%{$term}%")
                  ->orWhere('perihal', 'like', "%{$term}%");
            });
        }

        $items = $query->get();

        return view('surat-keluar.print', compact('items', 'search'));
    }
}
