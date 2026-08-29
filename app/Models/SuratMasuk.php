<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class SuratMasuk extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'surat_masuk';

    protected $fillable = [
        'no_agenda',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_terima',
        'asal_surat',
        'perihal',
        'file_surat',
        'file_pdf',
        'diterima_oleh_id',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
    ];

    /**
     * User who received the letter.
     */
    public function diterimaOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diterima_oleh_id');
    }

    /**
     * Format Agenda Number from the sequential no_agenda column.
     * Selalu berurutan 1,2,3... tanpa celah meski ada data yang dihapus.
     */
    public function getNoAgendaFormattedAttribute(): string
    {
        $num = $this->no_agenda ?? $this->id;
        return '#' . str_pad((string) $num, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check if letter has any uploaded file.
     */
    public function hasFile(): bool
    {
        return !empty($this->file_surat) || !empty($this->file_pdf);
    }

    /**
     * Get original file extension.
     */
    public function getOriginalExtensionAttribute(): string
    {
        if (empty($this->file_surat)) {
            return '';
        }
        return strtolower(pathinfo($this->file_surat, PATHINFO_EXTENSION));
    }

    /**
     * Get the original uploaded filename without path.
     */
    public function getOriginalFileNameAttribute(): string
    {
        if (empty($this->file_surat)) {
            return '';
        }
        return basename($this->file_surat);
    }

    /**
     * Check if original file is an image (jpg, jpeg, png).
     */
    public function isOriginalImage(): bool
    {
        return in_array($this->original_extension, ['jpg', 'jpeg', 'png'], true);
    }
}
