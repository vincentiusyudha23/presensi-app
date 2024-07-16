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
                            <table class="table" width="100%" cellspcasing="0" id="pao">
                                <thead>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($presensis as $year => $periods)
                                        @foreach ($periods as $periode => $count)
                                            <tr>
                                                <td>
                                                    <div class="row"
                                                        style="display: flex; justify-content: space-between; cursor:pointer;"
                                                        onclick="location.href='{{ url('admin/presensi/periode/' . $year . '/' . $periode) }}'">
                                                        <div class="col-6">List Presensi Periode Bulan {{ $periode }}
                                                            {{ $year }}
                                                        </div>
                                                        <div class="col-2">{{ $count }} Petugas</div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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
            $('#pao').DataTable({});
            $('#pao_length').hide();
            $('th').hide();
            $('#pao_info').parent().parent().hide();
        });
    </script>
@endpush
