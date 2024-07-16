@extends('layouts.master')
@section('title', 'Jadwal Petugas')
@section('content')
    <div class="col-xl-12 col-lg-11">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">List Jadwal Petugas</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Periode Bulan</th>
                                <th>Lokasi</th>
                                <th>Waktu</th>
                                <th>Hari</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jadwal as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->petugas->name }}</td>
                                    <td>{{ $item->petugas->nik }}</td>
                                    <td>{{ $item->periode }}</td>
                                    <td>{{ $item->lokasi }}</td>
                                    <td>{{ $item->waktu }}</td>
                                    <td>{{ $item->hari }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')
    <script>
        @if (session('success'))
            // swal("Berhasil!", "{{ session('success') }}", "success");
            var toastMixin = Swal.mixin({
                toast: true,
                icon: 'success',
                title: 'General Title',
                animation: false,
                position: 'top-right',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            toastMixin.fire({
                animation: true,
                title: '{{ session('success') }}'
            });
        @endif
        $('#periode_bulan').selectize({
            create: false
        });

        $('#exampleModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var nama = button.data('nama');
            var nik = button.data('nik');
            var periode = button.data('periode');
            var alamat = button.data('alamat');
            var waktu = button.data('waktu');
            var hari = button.data('hari');
            var modal = $(this);
            modal.find('.modal-body input[name=nama]').val(nama);
            modal.find('.modal-body input[name=nik]').val(nik);
            modal.find('.modal-body select[name=periode]').val(periode);
            modal.find('.modal-body input[name=lokasi]').val(alamat);
            modal.find('.modal-body input[name=waktu]').val(waktu);
            modal.find('.modal-body input[name=hari]').val(hari);
        });
    </script>
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
            <select class="custom-select" id="bulanFil" value="{{ $bulan }}">
                ${StringOptionMonths}
            </select>
        </div>
        <div class="input-group mx-1">
            <div class="input-group-prepend">
                <label class="input-group-text" for="inputGroupSelect01">Tahun</label>
            </div>
            <select class="custom-select" id="tahunFil" value="{{ $tahun }}">
                ${StringOptionYears}
            </select>
        </div>
    </label>`);

            // dataFilterBox.append(
            //     `<button class="btn btn-info" style="margin-left: 1rem"><i class="fas fa-plus"></i>  Tambah</button>`
            // );
            dataFilterBox.css({
                "display": "flex",
                "justify-content": "space-between",
                "align-items": "center"
            });

            $('#bulanFil, #tahunFil').change(() => {
                const bulan = $('#bulanFil').val();
                const tahun = $('#tahunFil').val();
                window.location.href = `{{ url('petugas/jadwal') }}?bulan=${bulan}&tahun=${tahun}`;
            })
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#dataTable_length').parent().hide()
            $('#dataTable_filter').parent().addClass('col-md-12')
            // $('#dataTable_info').parent().parent().prepend(`
        // <div class="col-12" style="display: flex;justify-content: right">
        //     <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
        //         Export Excel
        //     </button>
        // </div>
        // `)
        });
    </script>
@endpush

@push('styles')
    <link href="{{ url('/vendor/selectize-bootstrap4/dist/css/selectize.bootstrap4.css') }}" rel="stylesheet">
@endpush
