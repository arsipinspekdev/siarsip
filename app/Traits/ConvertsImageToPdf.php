<?php

declare(strict_types=1);

namespace App\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ConvertsImageToPdf
{
    /**
     * Handle document file upload.
     * If file is an image (jpg, jpeg, png), saves the original file AND generates a converted PDF.
     * If file is already a PDF, saves as PDF and sets both fields.
     * 
     * @return array{file_surat: string, file_pdf: ?string}
     */
    protected function handleDocumentUpload(UploadedFile $file, string $prefix = 'dokumen'): array
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $imageExtensions = ['jpg', 'jpeg', 'png'];
        $random = Str::random(24);

        if (in_array($extension, $imageExtensions, true)) {
            // 1. Simpan file gambar asli
            $originalFilename = "{$prefix}_original_{$random}.{$extension}";
            $originalPath = $file->storeAs('dokumen_surat', $originalFilename, 'public');

            // 2. Generate PDF dari gambar
            $imageData = base64_encode(file_get_contents($file->getRealPath()));
            $mimeType = $file->getMimeType();
            $base64 = 'data:' . $mimeType . ';base64,' . $imageData;

            $pdf = Pdf::loadView('pdf.image', ['imageBase64' => $base64])->setPaper('a4', 'portrait');

            $pdfFilename = "{$prefix}_pdf_{$random}.pdf";
            $pdfPath = 'dokumen_surat/' . $pdfFilename;
            Storage::disk('public')->put($pdfPath, $pdf->output());

            return [
                'file_surat' => $originalPath,
                'file_pdf'   => $pdfPath,
            ];
        }

        if ($extension === 'pdf') {
            $filename = "{$prefix}_{$random}.pdf";
            $path = $file->storeAs('dokumen_surat', $filename, 'public');

            return [
                'file_surat' => $path,
                'file_pdf'   => $path,
            ];
        }

        // Dokumen format lainnya (DOC, DOCX, dll)
        $filename = "{$prefix}_{$random}.{$extension}";
        $path = $file->storeAs('dokumen_surat', $filename, 'public');

        return [
            'file_surat' => $path,
            'file_pdf'   => null,
        ];
    }

    /**
     * Safely delete old documents from storage disk.
     */
    protected function deleteOldDocuments(?string $fileSurat, ?string $filePdf = null): void
    {
        if ($fileSurat && Storage::disk('public')->exists($fileSurat)) {
            Storage::disk('public')->delete($fileSurat);
        }

        if ($filePdf && $filePdf !== $fileSurat && Storage::disk('public')->exists($filePdf)) {
            Storage::disk('public')->delete($filePdf);
        }
    }
}
