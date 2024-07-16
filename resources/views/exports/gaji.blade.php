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
