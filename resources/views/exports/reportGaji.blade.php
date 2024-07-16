<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Report Gaji</title>
    <style>
        /* Tambahkan styling CSS sesuai kebutuhan */
        body {
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        h1,
        h2 {
            text-align: center;
        }

        .qrcode {
            text-align: center;
            margin-top: 20px;
        }

        .qrcode img {
            width: 100px;
            height: 100px;
        }

        .timestamp {
            text-align: right;
            font-size: 12px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <h1>Report Gaji</h1>
    <h2>bulan {{ $bulan }} tahun {{ $tahun }}</h2>
    <div class="timestamp">
        Dicetak pada: {{ $timestamp }}
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>NIK</th>
                    <th>Gaji</th>
                    <th>Potongan</th>
                    <th>Total Gaji</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($gajis as $item)
                    @if ($item->petugas == null)
                        @continue
                    @endif
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->petugas->name }}</td>
                        <td>{{ $item->petugas->nik }}</td>
                        <td style="white-space: nowrap">Rp.
                            {{ number_format($item->gaji, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->potongan . '%' }}</td>
                        <td style="white-space: nowrap">Rp.
                            {{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
