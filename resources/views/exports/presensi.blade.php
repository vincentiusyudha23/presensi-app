<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>Tanggal</th>
            <th>Lokasi</th>
            <th>Masuk</th>
            <th>Keluar</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($presensis as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->nik }}</td>
                <td>{{ $item->lokasi }}</td>
                <td>{{ $item->petugas->jadwal[0]->lokasi }}</td>
                <td>{{ date('H:i', strtotime($item->waktu_masuk)) }}</td>
                <td>{{ date('H:i', strtotime($item->waktu_keluar)) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
