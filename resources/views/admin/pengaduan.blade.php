@extends('layouts.master')

@section('title', 'Pengaduan')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-lg-11">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Pengaduan</h6>
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
                                        <th>Bukti Pendukung</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pengaduan as $item)
                                        @if ($item->petugas == null)
                                            @continue
                                        @endif
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->petugas->name }}</td>
                                            <td>{{ $item->petugas->nik }}</td>
                                            <td>{{ $item->tanggal }}</td>
                                            <td>{{ $item->lokasi }}</td>
                                            <td><img src="{{ url('storage/imgpengaduan/' . $item->foto) }}" alt=""
                                                    srcset="" style="width: 100px;height: auto;"></td>
                                            <td>{{ $item->keterangan }}</td>
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
            const dataFilterBox = $('#dataTable_filter');


        });
    </script>
    <script>
        $(document).ready(function() {
            // $('#dataTable_length').parent().hide()
            // $('#dataTable_filter').parent().addClass('col-md-12')
            $('#dataTable_info').parent().parent().prepend(`
            <div class="col-12" style="display: flex;justify-content: right">
                <a href="{{ url('admin/pengaduan/export') }}" class="btn btn-primary" >
                    Export Excel
                </a>
            </div>
            `)
        });
    </script>
@endpush
