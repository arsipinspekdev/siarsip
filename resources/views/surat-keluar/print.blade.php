<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekapitulasi Surat Keluar</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        /* ─── SCREEN LAYOUT ─────────────────────────────────────────── */
        @media screen {
            :root {
                --sidebar-w: 340px;
                --blue: #1d4ed8;
                --blue-dark: #1e40af;
                --bg: #f0f4f8;
            }

            body {
                font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
                background: var(--bg);
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            /* Top action bar */
            .topbar {
                background: #fff;
                border-bottom: 1px solid #e2e8f0;
                padding: 12px 24px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                position: sticky;
                top: 0;
                z-index: 50;
                box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            }
            .topbar-brand {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .topbar-brand-icon {
                width: 34px; height: 34px;
                background: linear-gradient(135deg, #10b981, #059669);
                border-radius: 8px;
                display: flex; align-items: center; justify-content: center;
                color: #fff;
                flex-shrink: 0;
            }
            .topbar-brand h1 {
                font-size: 14px; font-weight: 800;
                color: #0f172a; letter-spacing: -0.01em;
            }
            .topbar-brand p { font-size: 11px; color: #64748b; font-weight: 500; }
            .topbar-actions { display: flex; align-items: center; gap: 8px; }

            /* Buttons */
            .btn {
                display: inline-flex; align-items: center; gap: 6px;
                padding: 8px 16px; font-size: 12px; font-weight: 700;
                border-radius: 10px; border: none; cursor: pointer;
                transition: all 0.15s ease; text-decoration: none;
                font-family: inherit; white-space: nowrap;
            }
            .btn svg { flex-shrink: 0; }
            .btn-primary { background: #10b981; color: #fff; }
            .btn-primary:hover { background: #059669; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(16,185,129,.3); }
            .btn-secondary { background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; }
            .btn-secondary:hover { background: #e2e8f0; }
            .btn-ghost { background: transparent; color: #64748b; border: 1px solid #e2e8f0; }
            .btn-ghost:hover { background: #f8fafc; color: #334155; }
            .btn-danger-soft { background: #fff1f2; color: #be123c; border: 1px solid #fecdd3; }
            .btn-danger-soft:hover { background: #fecdd3; }

            /* Main content area */
            .main-wrap {
                flex: 1;
                display: flex;
                gap: 0;
                overflow: hidden;
                height: calc(100vh - 57px);
            }

            /* Settings sidebar */
            .settings-sidebar {
                width: var(--sidebar-w);
                flex-shrink: 0;
                background: #1e293b;
                color: #f1f5f9;
                overflow-y: auto;
                padding: 20px;
                display: flex;
                flex-direction: column;
                gap: 16px;
            }
            .sidebar-section {
                background: rgba(255,255,255,.05);
                border: 1px solid rgba(255,255,255,.08);
                border-radius: 12px;
                padding: 16px;
            }
            .sidebar-section-title {
                font-size: 10px; font-weight: 800;
                text-transform: uppercase; letter-spacing: .08em;
                color: #94a3b8; margin-bottom: 12px;
                display: flex; align-items: center; gap: 6px;
            }
            .sidebar-section-title span {
                flex: 1; border-top: 1px solid rgba(255,255,255,.08);
                margin-left: 4px;
            }
            .field-group { margin-bottom: 12px; }
            .field-group:last-child { margin-bottom: 0; }
            .field-label {
                display: block; font-size: 11px; font-weight: 700;
                color: #94a3b8; margin-bottom: 5px; letter-spacing: .02em;
            }
            .field-input {
                width: 100%; padding: 8px 12px;
                font-size: 12px; font-weight: 500; color: #f1f5f9;
                background: rgba(255,255,255,.06);
                border: 1px solid rgba(255,255,255,.12);
                border-radius: 8px; outline: none;
                font-family: inherit; transition: all .15s;
            }
            .field-input::placeholder { color: #475569; }
            .field-input:focus {
                border-color: #10b981;
                background: rgba(16,185,129,.1);
                box-shadow: 0 0 0 2px rgba(16,185,129,.2);
            }
            .hint-text {
                font-size: 10px; color: #64748b; margin-top: 4px;
            }
            .info-pill {
                display: inline-flex; align-items: center; gap: 5px;
                background: rgba(16,185,129,.15); border: 1px solid rgba(16,185,129,.25);
                color: #6ee7b7; padding: 5px 10px; border-radius: 8px;
                font-size: 11px; font-weight: 600;
                margin-bottom: 16px; width: 100%;
            }

            /* Preview area */
            .preview-area {
                flex: 1;
                overflow-y: auto;
                padding: 32px 40px;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .preview-label {
                display: inline-flex; align-items: center; gap: 6px;
                background: #10b981; color: #fff;
                padding: 4px 12px; border-radius: 20px;
                font-size: 11px; font-weight: 700; letter-spacing: .04em;
                margin-bottom: 20px; text-transform: uppercase;
            }

            /* Paper sheet */
            .paper-sheet {
                background: #fff;
                width: 100%; max-width: 820px;
                box-shadow: 0 20px 60px -10px rgba(0,0,0,.15), 0 4px 16px rgba(0,0,0,.08);
                border-radius: 4px;
                padding: 52px 56px;
                border: 1px solid #e2e8f0;
                font-family: 'Times New Roman', Times, serif;
                position: relative;
            }
            /* Subtle corner decoration */
            .paper-sheet::before {
                content: '';
                position: absolute; top: 0; right: 0;
                width: 0; height: 0;
                border-style: solid;
                border-width: 0 28px 28px 0;
                border-color: transparent #e2e8f0 transparent transparent;
            }
        }

        /* ─── DOCUMENT STYLES (both screen + print) ──────────────────── */
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .kop-pemerintah {
            font-size: 16pt; font-weight: bold; margin: 0;
            text-transform: uppercase; letter-spacing: .5px;
        }
        .kop-instansi {
            font-size: 14pt; font-weight: bold; margin: 3px 0;
            text-transform: uppercase;
        }
        .kop-alamat {
            font-size: 10pt; margin: 2px 0 0 0; color: #111827;
        }
        .doc-title {
            text-align: center; font-size: 14pt; font-weight: bold;
            margin: 18px 0 4px 0; text-decoration: underline;
            text-transform: uppercase;
        }
        .doc-subtitle {
            text-align: center; font-size: 11pt;
            margin-bottom: 18px; font-style: italic; color: #374151;
        }
        table.data-table {
            width: 100%; border-collapse: collapse;
            margin-top: 12px;
            font-family: 'Times New Roman', Times, serif;
        }
        table.data-table th,
        table.data-table td {
            border: 1px solid #000;
            padding: 6px 10px; font-size: 10.5pt; vertical-align: top;
        }
        table.data-table th {
            background-color: #f3f4f6;
            text-align: center; font-weight: bold; text-transform: uppercase;
        }
        table.data-table tr:nth-child(even) td { background: #fafafa; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .signature-section {
            margin-top: 40px; display: flex;
            justify-content: flex-end; page-break-inside: avoid;
        }
        .signature-box {
            text-align: center; width: 280px; font-size: 11pt;
        }
        .signature-space { height: 68px; }
        .signature-name {
            font-weight: bold; text-decoration: underline; margin-bottom: 2px;
        }

        /* ─── PRINT MEDIA ────────────────────────────────────────────── */
        @media print {
            .no-print, .topbar, .settings-sidebar, .preview-label {
                display: none !important;
            }
            body { background: #fff !important; }
            .main-wrap { height: auto !important; overflow: visible !important; }
            .preview-area { padding: 0 !important; overflow: visible !important; }
            .paper-sheet {
                box-shadow: none !important; border: none !important;
                padding: 0 !important; border-radius: 0 !important;
                max-width: 100% !important;
            }
            .paper-sheet::before { display: none !important; }
            table.data-table th {
                background-color: #e5e7eb !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            table.data-table tr:nth-child(even) td {
                background: #f9f9f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    {{-- ──── TOP ACTION BAR ──── --}}
    <header class="topbar no-print">
        <div class="topbar-brand">
            <div class="topbar-brand-icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </div>
            <div>
                <h1>Cetak Rekap Surat Keluar</h1>
                <p>Pratinjau langsung · Atur kop &amp; penandatangan di panel kiri</p>
            </div>
        </div>
        <div class="topbar-actions">
            <button type="button" onclick="resetToDefaults()" class="btn btn-danger-soft">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                Reset Bawaan
            </button>
            <button type="button" onclick="window.close()" class="btn btn-ghost">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Tutup
            </button>
            <button type="button" onclick="window.print()" class="btn btn-primary">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Dokumen
            </button>
        </div>
    </header>

    <div class="main-wrap">
        {{-- ──── SETTINGS SIDEBAR ──── --}}
        <aside class="settings-sidebar no-print">

            <div class="info-pill">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Pratinjau berubah otomatis saat mengetik
            </div>

            {{-- Kop Surat --}}
            <div class="sidebar-section">
                <div class="sidebar-section-title">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Kop Surat <span></span>
                </div>

                <div class="field-group">
                    <label class="field-label" for="input-pemerintah">Nama Pemerintah / Instansi Atas</label>
                    <input type="text" id="input-pemerintah" class="field-input" placeholder="PEMERINTAH KABUPATEN BOGOR" />
                </div>
                <div class="field-group">
                    <label class="field-label" for="input-instansi">Nama Dinas / Unit Kerja</label>
                    <input type="text" id="input-instansi" class="field-input" placeholder="DINAS KOMUNIKASI DAN INFORMATIKA" />
                </div>
                <div class="field-group">
                    <label class="field-label" for="input-alamat">Alamat, Telp &amp; Email</label>
                    <input type="text" id="input-alamat" class="field-input" placeholder="Jl. Raya No. 1 • Telp: (021) 12345 • Email: ..." />
                    <p class="hint-text">Pisahkan dengan tanda •</p>
                </div>
            </div>

            {{-- Penandatangan --}}
            <div class="sidebar-section">
                <div class="sidebar-section-title">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Penandatangan <span></span>
                </div>

                <div class="field-group">
                    <label class="field-label" for="input-tanggal">Tempat &amp; Tanggal</label>
                    <input type="text" id="input-tanggal" class="field-input" placeholder="Jakarta, 29 Agustus 2026" />
                </div>
                <div class="field-group">
                    <label class="field-label" for="input-jabatan">Jabatan</label>
                    <input type="text" id="input-jabatan" class="field-input" placeholder="Kepala Bagian Tata Usaha" />
                </div>
                <div class="field-group">
                    <label class="field-label" for="input-nama">Nama Pejabat</label>
                    <input type="text" id="input-nama" class="field-input" placeholder="Drs. H. Ahmad Fauzi, M.Si" />
                </div>
                <div class="field-group">
                    <label class="field-label" for="input-nip">NIP (Nomor Induk Pegawai)</label>
                    <input type="text" id="input-nip" class="field-input" placeholder="Kosongkan jika tidak diperlukan" />
                    <p class="hint-text">Awalan "NIP." akan ditambahkan otomatis</p>
                </div>
            </div>

            {{-- Info rekap --}}
            <div class="sidebar-section" style="background: rgba(16,185,129,.15); border-color: rgba(16,185,129,.2);">
                <div class="sidebar-section-title" style="color: #6ee7b7;">
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Info Rekap <span style="border-color: rgba(110,231,183,.2);"></span>
                </div>
                <p style="font-size: 12px; color: #a7f3d0; font-weight: 600;">
                    Total: {{ $items->count() }} Surat Keluar
                </p>
                @if(!empty($search))
                    <p style="font-size: 11px; color: #6ee7b7; margin-top: 4px;">
                        Filter: "{{ $search }}"
                    </p>
                @endif
            </div>
        </aside>

        {{-- ──── PREVIEW AREA ──── --}}
        <main class="preview-area">
            <div class="preview-label no-print">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                Pratinjau Dokumen
            </div>

            <div class="paper-sheet">
                {{-- KOP SURAT --}}
                <div class="kop-surat">
                    <h1 class="kop-pemerintah" id="preview-pemerintah">PEMERINTAH REPUBLIK INDONESIA</h1>
                    <h2 class="kop-instansi" id="preview-instansi">SISTEM PENGELOLAAN ARSIP SURAT KEDINASAN</h2>
                    <p class="kop-alamat" id="preview-alamat">Alamat Kantor Sekretariat Daerah &bull; Telp: (021) 12345678 &bull; Email: arsip@instansi.go.id</p>
                </div>

                {{-- JUDUL --}}
                <div class="doc-title">REKAPITULASI ARSIP SURAT KELUAR</div>
                <div class="doc-subtitle">
                    @if(!empty($search))Filter: &ldquo;{{ $search }}&rdquo; &bull; @endif
                    Total: {{ $items->count() }} Surat Keluar
                </div>

                {{-- TABEL --}}
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:4%">No.</th>
                            <th style="width:10%">Tgl. Surat</th>
                            <th style="width:11%">Sifat Surat</th>
                            <th style="width:16%">Pengirim</th>
                            <th style="width:11%">Tgl. Penomoran</th>
                            <th style="width:17%">Disposisi</th>
                            <th style="width:14%">Pengelola</th>
                            <th style="width:17%">Jenis Surat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($items as $index => $item)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $item->tanggal_surat?->format('d/m/Y') }}</td>
                                <td class="text-center">{{ $item->sifat_surat ?? '—' }}</td>
                                <td>{{ $item->pengirim ?? '—' }}</td>
                                <td class="text-center">{{ $item->tanggal_penomoran?->format('d/m/Y') ?? '—' }}</td>
                                <td>{{ $item->disposisi ?? '—' }}</td>
                                <td>{{ $item->pengelola ?? '—' }}</td>
                                <td>{{ $item->jenis_surat ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center" style="padding: 28px; color: #6b7280; font-style: italic;">
                                    Tidak ada data surat keluar yang sesuai.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- TANDA TANGAN --}}
                <div class="signature-section">
                    <div class="signature-box">
                        <p id="preview-tanggal">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        <p id="preview-jabatan" style="font-weight: bold; margin-top: 4px;">Kepala Bagian Tata Usaha</p>
                        <div class="signature-space"></div>
                        <p class="signature-name" id="preview-nama">{{ auth()->user()?->name ?? 'Administrator' }}</p>
                        <p id="preview-nip">NIP. ........................................</p>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        (function () {
            const defaultValues = {
                pemerintah: 'PEMERINTAH REPUBLIK INDONESIA',
                instansi:   'SISTEM PENGELOLAAN ARSIP SURAT KEDINASAN',
                alamat:     'Alamat Kantor Sekretariat Daerah \u2022 Telp: (021) 12345678 \u2022 Email: arsip@instansi.go.id',
                tanggal:    'Jakarta, {{ \Carbon\Carbon::now()->translatedFormat("d F Y") }}',
                jabatan:    'Kepala Bagian Tata Usaha',
                nama:       '{{ auth()->user()?->name ?? "Administrator" }}',
                nip:        ''
            };

            const fields = [
                { inputId: 'input-pemerintah', previewId: 'preview-pemerintah', key: 'cetak_pemerintah', def: defaultValues.pemerintah },
                { inputId: 'input-instansi',   previewId: 'preview-instansi',   key: 'cetak_instansi',   def: defaultValues.instansi },
                { inputId: 'input-alamat',     previewId: 'preview-alamat',     key: 'cetak_alamat',     def: defaultValues.alamat },
                { inputId: 'input-tanggal',    previewId: 'preview-tanggal',    key: 'cetak_tanggal',    def: defaultValues.tanggal,
                  fmt: v => v.trim() || defaultValues.tanggal },
                { inputId: 'input-jabatan',    previewId: 'preview-jabatan',    key: 'cetak_jabatan',    def: defaultValues.jabatan },
                { inputId: 'input-nama',       previewId: 'preview-nama',       key: 'cetak_nama',       def: defaultValues.nama },
                { inputId: 'input-nip',        previewId: 'preview-nip',        key: 'cetak_nip',        def: defaultValues.nip,
                  fmt: v => v.trim() ? 'NIP. ' + v.replace(/^NIP\.\s*/i, '') : 'NIP. ........................................' }
            ];

            fields.forEach(f => {
                const inp  = document.getElementById(f.inputId);
                const prev = document.getElementById(f.previewId);
                if (!inp || !prev) return;

                const saved = localStorage.getItem(f.key);
                const val   = saved !== null ? saved : f.def;
                inp.value          = val;
                prev.textContent   = f.fmt ? f.fmt(val) : val;

                inp.addEventListener('input', function () {
                    localStorage.setItem(f.key, this.value);
                    prev.textContent = f.fmt ? f.fmt(this.value) : (this.value || '—');
                });
            });

            window.resetToDefaults = function () {
                if (!confirm('Kembalikan pengaturan kop surat dan penandatangan ke teks bawaan?')) return;
                fields.forEach(f => {
                    localStorage.removeItem(f.key);
                    const inp  = document.getElementById(f.inputId);
                    const prev = document.getElementById(f.previewId);
                    if (!inp || !prev) return;
                    inp.value        = f.def;
                    prev.textContent = f.fmt ? f.fmt(f.def) : f.def;
                });
            };
        })();
    </script>
</body>
</html>
