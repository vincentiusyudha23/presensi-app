@extends('layouts.master')

@section('title', 'Akun Petugas')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12 col-lg-11">
                <div class="card shadow mb-1">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Buat Akun Petugas</h6>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body">
                        <form action="{{ url('admin/akun-petugas') }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="inputEmail4">Username</label>
                                    <input name="username" type="text" class="form-control">
                                    <label for="inputPassword4">Password</label>
                                    <input name="password" type="password" class="form-control">
                                    <label>Nama</label>
                                    <input name="nama" type="text" class="form-control" id="nama">
                                    <label>NIK</label>
                                    <input name="nik" type="text" class="form-control" id="nik">

                                </div>
                                <div class="form-group col-md-6">
                                    <label for="">Alamat</label>
                                    <input name="alamat" type="text" class="form-control" id="alamat">
                                    <label>Tanggal Lahir</label>
                                    <input name="tgl_lahir" type="date" class="form-control" id="tanggal_lahir">
                                    <label for="">Email</label>
                                    <input name="email" type="email" class="form-control" id="email">
                                    <label for="">No. Telepon</label>
                                    <input name="no_telp" type="text" class="form-control" id="no_telepon">
                                    <div class="row mt-4" style="display: flex;justify-content: center">
                                        <div class="col-md-3">
                                            <input type="button" data-toggle="modal" data-target="#importModal"
                                                class="btn btn-warning btn-user btn-block px-0 text-center"
                                                value="Import Akun">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="submit" class="btn btn-primary btn-user btn-block" value="submit">
                                        </div>
                                        <div class="col-md-3">
                                            <input type="reset" class="btn btn-danger btn-user btn-block" value="Batal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-lg-11">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">List Akun Petugas</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>NIK</th>
                                        <th>Tgl Lahir</th>
                                        <th>Alamat</th>

                                        <th>No. Telepon</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($petugas as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->nik }}</td>
                                            <td>{{ $item->tgl_lahir }}</td>
                                            <td>{{ $item->alamat }}</td>
                                            <td>{{ $item->no_telp }}</td>
                                            <td class="action-column" style="white-space: nowrap">
                                                <button data-toggle="modal" data-target="#exampleModal"
                                                    data-nama="{{ $item->name }}" data-nik="{{ $item->nik }}"
                                                    data-tgl-lahir="{{ $item->tgl_lahir }}"
                                                    data-alamat="{{ $item->alamat }}" data-telepon="{{ $item->no_telp }}"
                                                    data-email="{{ $item->email }}"
                                                    data-username="{{ $item->user->username }}"
                                                    data-action="{{ url('admin/akun-petugas/' . $item->id) }}"
                                                    class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <a data-confirm-delete="true"
                                                    href="{{ url('admin/akun-petugas/' . $item->id) }}"
                                                    class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
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
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Import Akun Petugas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group text-center"
                        style="display: flex;
                    justify-content: center;align-items: center">
                        <input type="file" class="form-control-file" id="fileImport">
                    </div>
                    <button hidden id="importSubmit" type="submit" class="btn btn-primary">Submit</button>
                    <div class="form-group text-center">

                        <table hidden class="table table-bordered" id="importTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>no</th>
                                    <th>Username</th>
                                    <th>Password</th>
                                    <th>Nama</th>
                                    <th>NIK</th>
                                    <th>Tanggal Lahir</th>
                                    <th>Alamat</th>
                                    <th>No. Telepon</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="modalForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Akun Petugas</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="inputUsername">Username</label>
                                <input name="username" type="text" class="form-control" id="inputUsername">
                                <label for="inputPassword">Password</label>
                                <input name="password" type="password" class="form-control" id="inputPassword">
                                <label for="inputNama">Nama</label>
                                <input name="nama" type="text" class="form-control" id="inputNama">
                                <label>NIK</label>
                                <input name="nik" type="text" class="form-control" id="inputNik">

                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputAlamat">Alamat</label>
                                <input name="alamat" type="text" class="form-control" id="inputAlamat">
                                <label for="inputTanggalLahir">Tanggal Lahir</label>
                                <input name="tgl_lahir" type="date" class="form-control" id="inputTanggalLahir">
                                <label for="inputEmail">Email</label>
                                <input name="email" type="email" class="form-control" id="inputEmail">
                                <label for="inputTelepon">No. Telepon</label>
                                <input name="no_telp" type="text" class="form-control" id="inputTelepon">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.sheetjs.com/xlsx-0.20.2/package/dist/xlsx.full.min.js"></script>
    <script>
        function fetchImport(data) {
            fetch("{{ url('admin/akun-petugas/import') }}", {
                    method: 'POST',
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-Token": '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        _token: '{{ csrf_token() }}',
                        data: data
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
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "searching": true,
            });
            $('#fileImport').change(() => {
                const file = document.getElementById('fileImport').files[0];
                const reader = new FileReader();
                reader.onload = function(e) {
                    const data = new Uint8Array(e.target.result);
                    const workbook = XLSX.read(data, {
                        type: 'array'
                    });
                    const sheet = workbook.Sheets[workbook.SheetNames[0]];
                    const json = XLSX.utils.sheet_to_json(sheet);
                    console.log(json);

                    document.getElementById('importTable').removeAttribute('hidden');
                    $('#importModal .modal-dialog')[0].classList.add('modal-xl');
                    $('#importModal .modal-dialog')[0].classList.add('modal-dialog-scrollable');

                    $('#importTable tbody').empty();
                    json.forEach((row, idx) => {
                        $('#importTable tbody').append(`
                            <tr>
                                <td>${idx+1}</td>
                                <td>generated by system</td>
                                <td>generated by system</td>
                                <td>${row.nama}</td>
                                <td>${row.nik}</td>

                                <td>${ new Date(Date.UTC(0, 0, row['tgl lahir'] - 1)).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })}</td>
                                <td>${row.alamat}</td>
                                <td>${row['no telepon']}</td>
                                <td>${row.email}</td>
                            </tr>
                        `);
                    });
                    document.getElementById('importSubmit').removeAttribute('hidden');
                    document.getElementById('importSubmit').addEventListener('click', () => {
                        fetchImport(json);
                    });
                };
                reader.readAsArrayBuffer(file);
            });
        });
    </script>
    <script>
        // Tunggu hingga DOM siap
        document.addEventListener('DOMContentLoaded', function() {
            // Seleksi semua tombol edit
            var editButtons = document.querySelectorAll('button[data-toggle="modal"]');

            // Tambahkan event listener pada setiap tombol
            editButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    // Ambil data dari atribut data-
                    var nama = button.getAttribute('data-nama');
                    var nik = button.getAttribute('data-nik');
                    var tanggalLahir = button.getAttribute('data-tgl-lahir');
                    var alamat = button.getAttribute('data-alamat');
                    var telepon = button.getAttribute('data-telepon');
                    var email = button.getAttribute('data-email');
                    var username = button.getAttribute('data-username');

                    document.getElementById('inputNama').value = nama;
                    document.getElementById('inputNik').value = nik;
                    document.getElementById('inputTanggalLahir').value = tanggalLahir;
                    document.getElementById('inputAlamat').value = alamat;
                    document.getElementById('inputTelepon').value = telepon;

                    document.getElementById('inputUsername').value = username;
                    document.getElementById('inputEmail').value = email;
                    document.getElementById('modalForm').action = button.getAttribute(
                        'data-action');
                });
            });
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
        @if (session('error'))
            // swal("Gagal!", "{{ session('error') }}", "error");
            var toastMixin = Swal.mixin({
                toast: true,
                icon: 'error',
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
                title: '{{ session('error') }}'
            });
        @endif
    </script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
@endpush
@push('styles')
    <style>
        /* Set a fixed width for the action column */
        .action-column {
            width: 120px;
            white-space: nowrap;
        }

        /* Ensure buttons are displayed inline */
        .action-column button {
            margin: 0 2px;
        }
    </style>
@endpush
