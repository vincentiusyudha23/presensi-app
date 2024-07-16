<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>Tanggal</th>
            <th>Lokasi</th>
            <th>Bukti Pendukung</th>
            <th>Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->petugas->name }}</td>
                <td>{{ $item->petugas->nik }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->lokasi }}</td>
                <td><a
                        href="{{ url('storage/imgpengaduan/' . $item->foto) }}">{{ url('storage/imgpengaduan/' . $item->foto) }}</a>
                </td>

                <td>{{ $item->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
