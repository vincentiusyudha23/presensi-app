@extends('layouts.master')

@section('title', 'Presensi')

@section('content')
    <div class="container-fluid">
        {{-- <form id="formPresensi" action="{{ url('petugas/presensi') }}" method="post" enctype="multipart/form-data"> --}}
        {{-- @csrf --}}
        <input type="image" id="subIm" name="subIm" hidden>
        <div class="row">
            <div class="col-xl-12 col-lg-11">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Data Presensi Petugas</h6>
                    </div>
                    <div class="card-body"
                        style="display: flex;
                    justify-content: center;align-items: center">
                        <img id="imgres" hidden
                            style="
                        max-width: 240px;
                        max-height: 280px;
                        width: 100%;
                        height: auto;
                        object-fit: cover;
                        "
                            class="img-fluid">
                        <div id="cam" style="
                        border:"></div>
                    </div>
                    <div class="mb-2 row" style="display: flex;align-items: center;justify-content: center">
                        <button id="take" class="btn btn-success">Foto</button>
                        <button class="btn btn-secondary" id="reset" hidden>Reset</button>
                        {{-- @dd($todayPresensi) --}}
                        <div class="col-12" style="display: flex;align-items: center;justify-content: center">
                            @if ($todayPresensi?->bukti_keluar != null && $todayPresensi?->waktu_masuk != null)
                                <button class="btn
                            btn-primary mt-2" id="submitbtn" hidden
                                    disabled>
                                    anda telah presensi keluar hari ini
                                </button>
                            @else
                                <button class="btn btn-primary mt-2" id="submitbtn" hidden>
                                    @if ($todayPresensi?->waktu_masuk == null)
                                        Absen Masuk
                                    @else
                                        @if ($todayPresensi?->bukti_keluar == null)
                                            Absen Keluar
                                        @endif
                                    @endif
                                </button>
                            @endif
                        </div>
                    </div>
                    <a href="{{ url('petugas/presensi/riwayat/' . Auth::user()->id) }}" class="btn btn-warning">Lihat
                        Riwayat
                        Presensi</a>
                </div>
            </div>
        </div>

        {{-- </form> --}}
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script>
    <script>
        function initcam() {
            Webcam.set({
                width: 240,
                height: 280,
                image_format: 'jpeg',
                jpeg_quality: 90
            });
            Webcam.attach('#cam');
        }
        initcam();
        document.getElementById('take').addEventListener('click', function(event) {
            event.preventDefault();
            Webcam.snap(function(data_uri) {
                document.getElementById('cam').innerHTML = '<img src="' + data_uri + '"/>';
                document.getElementById('subIm').src = data_uri;
            });
            document.getElementById('reset').removeAttribute('hidden');
            document.getElementById('take').setAttribute('hidden', true);
            document.getElementById('submitbtn').removeAttribute('hidden');
        });
        document.getElementById('reset').addEventListener('click', function(event) {
            event.preventDefault();
            initcam();
            document.getElementById('reset').setAttribute('hidden', true);
            document.getElementById('take').removeAttribute('hidden');
            document.getElementById('subIm').removeAttribute('src');
            document.getElementById('submitbtn').setAttribute('hidden', true);
        });
        document.getElementById('submitbtn').addEventListener('click', function(event) {
            event.preventDefault();
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data yang sudah di submit tidak dapat diubah!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Submit'
            }).then(result => {
                if (result.isConfirmed) {
                    console.log('submit');
                    fetch("{{ url('petugas/presensi') }}", {
                            method: 'POST',
                            headers: {
                                "Content-Type": "application/json",
                                "Accept": "application/json",
                                "X-Requested-With": "XMLHttpRequest",
                                "X-CSRF-Token": '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                _token: '{{ csrf_token() }}',
                                subIm: document.getElementById('subIm').src
                            })
                        })
                        .then(res => res.json())
                        .then(res => {
                            if (res.status != 'error') {
                                Swal.fire({
                                        title: "Berhasil!",
                                        text: "Kamu telah melakukan presensi",
                                        icon: "success"
                                    })
                                    .then(result => {
                                        if (result.isConfirmed) {
                                            window.location.reload();
                                        }
                                    });

                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: "Terdapat Masalah!",
                                    icon: "danger"
                                });
                            }
                        })
                }
            })
        });
    </script>
@endpush
