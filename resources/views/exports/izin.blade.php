<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>Tanggal</th>
            <th>Jenis Izin/Cuti</th>
            <th>Keterangan</th>
            <th>status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($izin as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->petugas->name }}</td>
                <td>{{ $item->petugas->nik }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->jenis }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>{{ $item->status }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
