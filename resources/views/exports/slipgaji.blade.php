<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Slip Gaji</title>
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

        h1 {
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
    <h1>Slip Gaji</h1>
    <div class="timestamp">
        Dicetak pada: {{ $data->timestamp }}
    </div>
    <table>
        <tr>
            <th>Nama</th>
            <td>{{ $data->nama }}</td>
        </tr>
        <tr>
            <th>Bulan/Tahun</th>
            <td>{{ $data->bulanTahun }}</td>
        </tr>
        <tr>
            <th>Total Hadir</th>
            <td>{{ $data->hadir }}</td>
        </tr>
        <tr>
            <th>Total Absen</th>
            <td>{{ $data->absen }}</td>
        </tr>
        <tr>
            <th>Total Potongan</th>
            <td>{{ $data->potongan }}</td>
        </tr>
        <tr>
            <th>Total Gaji</th>
            <td>Rp. {{ $data->total }}</td>
        </tr>
    </table>

    <div class="qrcode">
        <img src="{{ $data->qrcode }}" alt="QR Code" />
    </div>
</body>

</html>
