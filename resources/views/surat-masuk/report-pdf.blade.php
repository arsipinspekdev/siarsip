<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Data Surat Masuk</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #111;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header h1 {
            font-size: 14pt;
            margin: 0;
            text-transform: uppercase;
        }
        .header h2 {
            font-size: 11pt;
            margin: 3px 0;
            font-weight: normal;
        }
        .title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            margin: 10px 0;
            text-decoration: underline;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #333;
            padding: 5px 6px;
            font-size: 9pt;
        }
        th {
            background-color: #f1f5f9;
            text-align: center;
            font-weight: bold;
        }
        .text-center { text-align: center; }
        .footer {
            margin-top: 20px;
            float: right;
            text-align: center;
            width: 220px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>PEMERINTAH REPUBLIK INDONESIA</h1>
        <h2>SISTEM PENGELOLAAN ARSIP SURAT KEDINASAN</h2>
    </div>

    <div class="title">REKAPITULASI ARSIP SURAT MASUK</div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No.</th>
                <th style="width: 12%;">No. Agenda</th>
                <th style="width: 20%;">Nomor Surat</th>
                <th style="width: 12%;">Tgl. Terima</th>
                <th style="width: 23%;">Asal Pengirim</th>
                <th style="width: 28%;">Perihal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center">{{ $item->no_agenda_formatted }}</td>
                    <td><strong>{{ $item->nomor_surat }}</strong></td>
                    <td class="text-center">{{ $item->tanggal_terima?->format('d/m/Y') }}</td>
                    <td>{{ $item->asal_surat }}</td>
                    <td>{{ $item->perihal }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 15px;">Tidak ada data surat masuk.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ date('d/m/Y H:i') }}</p>
        <br><br>
        <p style="font-weight: bold; text-decoration: underline;">
            {{ auth()->user()?->name ?? 'Petugas Tata Usaha' }}
        </p>
    </div>
</body>
</html>
