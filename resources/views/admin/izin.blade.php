@extends('layouts.master')

@section('title', 'Izin/Cuti')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-lg-11">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Pengajuan Izin/Cuti</h6>
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
                                        <th>Jenis Izin/Cuti</th>
                                        <th>Keterangan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($izin as $item)
                                        @if ($item->petugas == null)
                                            @continue
                                        @endif
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->petugas->name }}</td>
                                            <td>{{ $item->petugas->nik }}</td>
                                            <td>{{ $item->tanggal }}</td>
                                            <td>{{ $item->jenis }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td class="action-column" style="white-space: nowrap">
                                                @if ($item->status == 'disetujui')
                                                    <span class="badge badge-success">disetujui</span>
                                                @endif
                                                @if ($item->status == 'ditolak')
                                                    <span class="badge badge-danger">ditolak</span>
                                                @endif
                                                @if ($item->status == 'pending')
                                                    <button data-action="{{ url('admin/izin/' . $item->id . '/1') }}"
                                                        data-confirm-izin="true" class="btn btn-sm btn-primary">
                                                        <i data-action="{{ url('admin/izin/' . $item->id . '/1') }}"
                                                            class="fas fa-check"></i>
                                                    </button>
                                                    <a data-action="{{ url('admin/izin/' . $item->id . '/2') }}"
                                                        data-confirm-delet="true" class="btn btn-sm btn-danger">
                                                        <i data-action="{{ url('admin/izin/' . $item->id . '/2') }}"
                                                            class="fas fa-times"></i>
                                                    </a>
                                                @endif
                                            </td>
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

            });

            $('button[data-confirm-izin]').click(function(event) {
                const button = $(event.target)
                const action = button.data('action')

                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Apakah Anda ingin melanjutkan aksi ini?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, lanjutkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = action;
                    }
                });
            });
            $('[data-confirm-delet]').click(function(event) {
                const button = $(event.target)
                const action = button.data('action')
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Tindakan ini tidak dapat diurungkan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // console.log('but', button);
                        // console.log('act', action);
                        window.location.href = action;
                    }
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#dataTable_length').parent().hide()
            $('#dataTable_filter').parent().addClass('col-md-12')
            $('#dataTable_info').parent().parent().prepend(`
            <div class="col-12" style="display: flex;justify-content: right">
                <a href="{{ url('admin/izin/export') }}" class="btn btn-primary">
                    Export Excel
                </a>
            </div>
            `)
        });
    </script>
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
    </script>
@endpush
