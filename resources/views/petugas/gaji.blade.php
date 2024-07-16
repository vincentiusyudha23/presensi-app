@extends('layouts.master')

@section('title', 'Riwayat Presensi')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-lg-11">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Gaji</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">

                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Gaji</th>
                                        <th>Potongan</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($gaji as $item)
                                        <tr>
                                            <td>{{ Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') }}
                                            </td>
                                            <td>Rp.
                                                {{ number_format($item->gaji, 0, ',', '.') }}</td>
                                            <td class="text-center">{{ $item->potongan . '%' }}</td>
                                            <td style="white-space: nowrap">Rp.
                                                {{ number_format($item->total, 0, ',', '.') }}</td>
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
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "pageLength": 5,
                "pagingType": "simple_numbers",
            });
            const arrayMonths = [
                'Januari',
                'Februari',
                'Maret',
                'April',
                'Mei',
                'Juni',
                'Juli',
                'Agustus',
                'September',
                'Oktober',
                'November',
                'Desember'
            ]
            const StringOptionMonths = arrayMonths.map((item) => {
                return `<option ${item==="{{ $bulan }}"?'selected':''} value="${item}">${item}</option>`
            }).join('')
            const mapYears = [
                2024,
                2023,
                2022,
            ]
            const StringOptionYears = mapYears.map((item) => {
                return `<option ${item=={{ $tahun }}?'selected':''} value="${item}">${item}</option>`
            }).join('')
            const dataFilterBox = $('#dataTable_filter');
            dataFilterBox.prepend(`<label style="display: flex;margin-bottom: 0.5rem;align-items: center;">Filter:
            <div class="input-group mx-1">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="inputGroupSelect01">Bulan</label>
                </div>
                <select class="custom-select" id="bulanFil">
                   ${StringOptionMonths}
                </select>
            </div>
            <div class="input-group mx-1">
                <div class="input-group-prepend">
                    <label class="input-group-text" for="inputGroupSelect01">Tahun</label>
                </div>
                <select class="custom-select" id="tahunFil">
                    ${StringOptionYears}
                </select>
            </div>
        </label>`);
            $('#bulanFil, #tahunFil').change(() => {
                const bulan = $('#bulanFil').val();
                const tahun = $('#tahunFil').val();
                const uri = window.location.href.split('?')[0];
                window.location.href = `${uri}?bulan=${bulan}&tahun=${tahun}`;
            })
            dataFilterBox.css({
                "display": "flex",
                "justify-content": "space-between",
                "align-items": "center"
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#dataTable_length').parent().hide()
            $('#dataTable_filter').parent().addClass('col-md-12')
            $('#dataTable_info').parent().parent().prepend(`
        <div class="col-12" style="display: flex;justify-content: right">
            <a href="{{ url('petugas/gaji/' . Auth::user()->petugas->id . '/export') }}?bulan={{ $bulan }}&tahun={{ $tahun }}" class="btn btn-primary">
                Export Excel
            </a>
        </div>
        `)
        });
    </script>
@endpush
