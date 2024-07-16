@extends('layouts.master')

@section('title', 'Presensi')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-lg-11">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Presensi Petugas</h6>
                    </div>
                    <div class="card-body">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
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
                                        @if ($item->petugas == null)
                                            @continue
                                        @endif
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->nik }}</td>
                                            <td>{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
                                            <td>{{ $item->petugas->jadwal[0]->lokasi }}</td>
                                            <td>{{ date('H:i', strtotime($item->waktu_masuk)) }}</td>
                                            <td>{{ date('H:i', strtotime($item->waktu_keluar)) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

@endsection
@push('scripts')
    <script>
        const monthMap = {
            'Januari': 1,
            'Februari': 2,
            'Maret': 3,
            'April': 4,
            'Mei': 5,
            'Juni': 6,
            'Juli': 7,
            'Agustus': 8,
            'September': 9,
            'Oktober': 10,
            'November': 11,
            'Desember': 12
        };

        // Peta balik dari nomor bulan ke nama bulan
        const reverseMonthMap = Object.keys(monthMap).reduce((acc, month) => {
            acc[monthMap[month]] = month;
            return acc;
        }, {});

        // Fungsi untuk mendapatkan rentang bulan dari string
        function getMonthsInRange(rangeString) {
            const [startMonth, endMonth] = rangeString.split('-').map(month => month.trim());
            const startMonthNumber = monthMap[startMonth];
            const endMonthNumber = monthMap[endMonth];

            // Hasilkan array bulan
            const monthsInRange = [];
            for (let i = startMonthNumber; i <= endMonthNumber; i++) {
                monthsInRange.push(reverseMonthMap[i]);
            }

            return monthsInRange;
        }
        let bulans = window.location.href.substring(window.location.href.lastIndexOf('/') + 1);
        let selectedMonth = bulans.split('?bulan=')[1];
        if (bulans.includes('?')) {
            bulans = bulans.substring(0, bulans.indexOf('?'));
        }
        const months = getMonthsInRange(bulans);

        const OptionsMonthsString = months.map(month =>
            `<option ${(selectedMonth===month?'selected':'')} value="${month}">${month}</option>`).join('');

        $(document).ready(function() {
            $('#dataTable').DataTable({

            });
            const dataFilterBox = $('#dataTable_filter');
            dataFilterBox.prepend(`<label style="display: flex;margin-bottom: 0.5rem;align-items: center;">Filter:
        <div class="input-group mx-1">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01">Bulan</label>
            </div>
            <select class="custom-select" id="monthInput">
                <option value='' selected>Pilih</option>
                ${OptionsMonthsString}
            </select>
        </div>
    </label>`);
            dataFilterBox.css({
                "display": "flex",
                "justify-content": "space-between",
                "align-items": "center"
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#monthInput').change(function() {
                const selectedMonth = $(this).val();
                let uri = window.location.href.split('?')[0];

                window.location.href = uri + '?bulan=' + selectedMonth;
            });
            $('#dataTable_length').parent().hide()
            $('#dataTable_filter').parent().addClass('col-md-12')
            $('#dataTable_info').parent().parent().prepend(`
            <div class="col-12" style="display: flex;justify-content: right">
                <a href="${window.location.href+'/export'}" id="export" class="btn btn-primary">
                    Export Excel
                </a>
            </div>
            `);

        });
    </script>
@endpush
