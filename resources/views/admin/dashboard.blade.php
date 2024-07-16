@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-2">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100 pb-0">
                    <div class="card-body" style="background: blue; border-radius:.35rem .35rem 0 0">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Total Petugas</div>
                                <div class="h3 mb-0 font-weight-bold text-white">{{ $totalPetugas }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer"
                        style="display: flex;justify-content: space-between; padding:.5rem;
                    border: 1px solid blue;border-radius:0 0 .35rem .35rem">
                        <a href="#" style="color: blue">view detail</a>
                        <p style="color: gray; margin:0">03/07/2024</p>
                    </div>
                </div>
            </div>
            <!-- Pending Requests Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body" style="background: green; border-radius:.35rem .35rem 0 0">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Total Hadir</div>
                                <div class="h3 mb-0 font-weight-bold text-white">{{ $totalHadirHariIni }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer"
                        style="display: flex;justify-content: space-between; padding:.5rem;
                    border: 1px solid green;border-radius:0 0 .35rem .35rem">
                        <a href="#" style="color: green">view detail</a>
                        <p style="color: gray; margin:0">03/07/2024</p>
                    </div>
                </div>
            </div>
            <!-- Earnings (Monthly) Card Example -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card shadow h-100">
                    <div class="card-body" style="background: yellow; border-radius:.35rem .35rem 0 0">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">Persentase Kehadiran
                                </div>
                                <div class="row no-gutters align-items-center">
                                    <div class="col-auto">
                                        <div class="h3 mb-0 mr-3 font-weight-bold text-white">
                                            {{ floor($presentasiKehadiran) }}%
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="progress progress-sm mr-2">
                                            <div class="progress-bar bg-white" role="progressbar"
                                                style="width: {{ $presentasiKehadiran }}%"
                                                aria-valuenow="{{ $presentasiKehadiran }}" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer"
                        style="display: flex;justify-content: space-between; padding:.5rem;
                    border: 1px solid yellow;border-radius:0 0 .35rem .35rem">
                        <a href="#" style="color: yellow">view detail</a>
                        <p style="color: gray; margin:0">03/07/2024</p>
                    </div>
                </div>
            </div>


        </div>
        <!-- Content Row -->

        <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-11">
                <div class="card shadow mb-1">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Grafik Presensi Petugas</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <div class="chart-area" style="display: flex; justify-content: center">
                            <canvas id="chartPresensi"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">

            <!-- Content Column -->
            <div class="col-xl-12 col-lg-11">

                <!-- Project Card Example -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">List Presensi Petugas</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Shift</th>
                                        <th>Waktu</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($presensiHariIni as $item)
                                        {{-- @dd($item->petugas->jadwal) --}}
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('Y-m-d', strtotime($item->created_at)) }}</td>
                                            <td>{{ $item->petugas->name }}</td>
                                            <td>{{ $item->petugas->nik }}</td>
                                            <td>{{ $item->petugas->jadwal[0]->waktu ?? '' }}</td>
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
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
    <script>
        var totalHadirPerhariSelamaSeminggu = @json($totalHadirPerhariSelamaSeminggu);

        const dataKehadiran = [10, 15, 8, 12, 20, 5]; // Contoh data

        // Membuat chart
        const ctx = document.getElementById('chartPresensi').getContext('2d');
        const kehadiranChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
                datasets: [{
                    data: Object.values(totalHadirPerhariSelamaSeminggu),
                    borderColor: "rgba(78, 115, 223, 1)",
                    backgroundColor: "rgba(78, 115, 223, 0.2)",
                    fill: false,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        ticks: {
                            beginAtZero: true,
                            // max: 60,
                            // callback: (val, i, ticks) => (i < ticks.length - 1 ? val : null)
                            callback: function(value) {
                                if (value % 1 === 0) {
                                    return value;
                                }
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'bottom',
                        offset: 10,
                        color: '#1cc88a',
                        font: {
                            weight: 'bold'
                        },
                        formatter: (value, ctx) => {
                            return value;
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
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
