<?php

declare(strict_types=1);

namespace App\Http\Requests\SuratMasuk;

use Illuminate\Foundation\Http\FormRequest;

final class UpdateSuratMasukRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nomor_surat'    => ['required', 'string', 'max:100'],
            'tanggal_surat'  => ['required', 'date'],
            'tanggal_terima' => ['required', 'date'],
            'asal_surat'     => ['required', 'string', 'max:255'],
            'perihal'        => ['required', 'string'],
            'file_surat'     => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', 'max:102400'],
        ];
    }

    public function messages(): array
    {
        return [
            'nomor_surat.required'    => 'Nomor surat wajib diisi.',
            'nomor_surat.max'         => 'Nomor surat maksimal 100 karakter.',
            'tanggal_surat.required'  => 'Tanggal surat wajib diisi.',
            'tanggal_surat.date'      => 'Format tanggal surat tidak valid.',
            'tanggal_terima.required' => 'Tanggal terima surat wajib diisi.',
            'tanggal_terima.date'     => 'Format tanggal terima tidak valid.',
            'asal_surat.required'     => 'Asal surat / instansi pengirim wajib diisi.',
            'asal_surat.max'          => 'Asal surat maksimal 255 karakter.',
            'perihal.required'        => 'Perihal surat wajib diisi.',
            'file_surat.file'         => 'Lampiran harus berupa file yang valid.',
            'file_surat.mimes'        => 'Format file harus berupa: PDF, DOC, DOCX, XLS, XLSX, JPG, atau PNG.',
            'file_surat.max'          => 'Ukuran file lampiran maksimal 100MB.',
        ];
    }
}
