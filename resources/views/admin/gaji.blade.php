@extends('layouts.master')

@section('title', 'Penggajian')

@section('content')
    <div class="container-fluid">
        <div class="col-xl-12 col-lg-11">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Data Gaji Petugas</h6>
                </div>
                {{-- @dd($gajis) --}}
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIK</th>
                                    <th>Gaji</th>
                                    <th>Potongan</th>
                                    <th>Total Gaji</th>
                                    <th>Aksi</th>
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
                                        <td class="action-column" style="white-space: nowrap">
                                            {{-- <button data-confirm-izin="true" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-signal"></i>
                                                </button> --}}
                                            <button data-toggle="modal" data-target="#detailGaji"
                                                data-id="{{ $item->id }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <a id="print"
                                                data-href="{{ url('admin/gaji/' . $item->id . '/export?bulan=' . $bulan . '&tahun=' . $tahun) }}"
                                                class="btn btn-sm btn-secondary">
                                                <i class="fas fa-print"></i>
                                            </a>
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
    <!-- Modal -->
    <div class="modal fade" id="detailGaji" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editGajiTitle"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body row">
                    <div class="col-3" style="white-space: nowrap;">
                        <p>Bulan/Tahun</p>
                        <p>Total Hadir</p>
                        <p>Total Absen</p>
                        <p>Total Gaji</p>
                        <p>Total Potongan</p>
                    </div>
                    <div class="col-1">
                        <p>:</p>
                        <p>:</p>
                        <p>:</p>
                        <p>:</p>
                        <p>:</p>
                    </div>
                    <div class="col">
                        <p id="bulanTahun"></p>
                        <p id="totalHadir"></p>
                        <p id="totalAbsent"></p>
                        <p id="totalGaji"></p>
                        <p id="totalPotongan"></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <div id="qr" hidden style="display: none,position:fixed;top:50vh;left:50vw" tabindex="-1"></div>
    <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
    <script>
        // Tunggu hingga DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            // Seleksi semua tombol edit
            const detailButt = document.querySelectorAll('button[data-target="#detailGaji"]');
            detailButt.forEach(function(but) {
                but.addEventListener('click', function() {
                    const id = but.getAttribute('data-id');
                    fetch(
                            `{{ url('admin/gaji') }}/${id}?bulan={{ $bulan }}&tahun={{ $tahun }}`
                        )
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                            document.getElementById('editGajiTitle').textContent =
                                `Detail Gaji ${data.bulanTahun} ${data.petugasNama}`;
                            document.getElementById('bulanTahun').textContent = data.bulanTahun;
                            document.getElementById('totalHadir').textContent =
                                `${data.totalHadir}`;

                            document.getElementById('totalAbsent').textContent =
                                `${data.totalAbsen}`;
                            document.getElementById('totalGaji').textContent =
                                `Rp. ${data.totalGaji}`;
                            document.getElementById('totalPotongan').textContent =
                                `${data.totalPotongan}`;
                        })
                })
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({

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
            dataFilterBox.append(
                `<div><a href="{{ url('admin/gaji/regenerate') }}" class="btn btn-primary" style="margin-left: 1rem"><i class="fas fa-sync-alt"></i> Refresh</a>
                <a href="{{ url('admin/gaji/export/?bulan=' . $bulan . '&tahun=' . $tahun) }}" class="btn btn-secondary" style="margin-left: 1rem"><i class="fas fa-print"></i>  Cetak</a></div>`
            );

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
                window.location.href = `{{ url('admin/gaji') }}?bulan=${bulan}&tahun=${tahun}`;
            })
        });
    </script>
    <form id="formqr" hidden method="POST" enctype="multipart/form-data">
        @csrf
        <input hidden type="file" id="qr" name="qr">
        <input type="text" hidden id="qrb64" name="qrb64">
        <input type="text" hidden id="path" name="uuid">
    </form>
    <script>
        $(document).ready(function() {
            const bulan = $('#bulanFil').val();
            const tahun = $('#tahunFil').val();
            $('#dataTable_length').parent().hide()
            $('#dataTable_filter').parent().addClass('col-md-12')
            $('#dataTable_info').parent().parent().prepend(`
        <div class="col-12" style="display: flex;justify-content: right">
            <a href="{{ url('admin/gaji/exportExcel') }}?bulan=${bulan}&tahun=${tahun}" class="btn btn-primary">
                Export Excel
            </a>
        </div>
        `)
            const btnPrint = document.getElementById('print');
            btnPrint.addEventListener('click', function(e) {
                e.preventDefault();
                // const myCanvas = document.createElement('canvas')
                // document.getElementsByTagName('body')[0].appendChild(myCanvas)
                const uuid = crypto.randomUUID();
                const path = `{{ url('storage/slipgaji') }}/${uuid}.pdf`;
                const qrcode = new QRCode(document.getElementById("qr"), {
                    text: path,
                    logo: "{{ url('') }}/img/dlh.png",
                    logoWidth: undefined,
                    logoHeight: undefined,
                    logoBackgroundColor: '#ffffff',
                    logoBackgroundTransparent: false
                });
                const qrcan = $('#qr canvas');
                const b64im = qrcan[0].toDataURL();
                const form = document.getElementById('formqr');
                const input = form.querySelector('input[type="file"]');
                document.getElementById('path').value = uuid;
                document.getElementById('qr').value = b64im;
                document.getElementById('qrb64').value = b64im;
                form.action = btnPrint.getAttribute('data-href');
                input.src = b64im;
                form.submit();
            })
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
