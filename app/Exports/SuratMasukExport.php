<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\SuratMasuk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

final class SuratMasukExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected ?string $search;

    public function __construct(?string $search = null)
    {
        $this->search = $search;
    }

    public function collection()
    {
        $query = SuratMasuk::with('diterimaOleh')->orderBy('no_agenda', 'asc');

        if ($this->search) {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->where('nomor_surat', 'like', "%{$term}%")
                  ->orWhere('asal_surat', 'like', "%{$term}%")
                  ->orWhere('perihal', 'like', "%{$term}%");
            });
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'No. Agenda',
            'Nomor Surat',
            'Tanggal Surat',
            'Tanggal Terima',
            'Asal Surat / Pengirim',
            'Perihal',
            'Penerima / Petugas',
            'Status Lampiran',
        ];
    }

    public function map($row): array
    {
        return [
            $row->no_agenda_formatted,
            $row->nomor_surat,
            $row->tanggal_surat?->format('d/m/Y') ?? '-',
            $row->tanggal_terima?->format('d/m/Y') ?? '-',
            $row->asal_surat,
            $row->perihal,
            $row->diterimaOleh?->name ?? '-',
            $row->file_surat ? 'Ada Lampiran' : 'Tidak Ada',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 12],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'DBEAFE'],
                ],
            ],
        ];
    }
}
