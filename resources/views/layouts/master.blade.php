<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DLHK | @yield('title')</title>

    <!-- Favicons -->
    <link href="{{ asset('img/favicon.ico') }}" rel="icon">
    <link href="{{ asset('img/apple-touch-icon.png') }}" rel="apple-touch-icon">

    <!-- Custom fonts for this template-->
    <link href="{{ url('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
        integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    {{-- <link rel="stylesheet" href="{{ url('vendor/bootstrap/scss/bootstrap.scss') }}"> --}}
    <!-- Custom styles for this template-->
    <link href="{{ url('css/sb-admin-2.css') }}" rel="stylesheet">
    <style>
        .navbar-default {
            position: fixed;
            width: 100%;
            margin: 0 0 405px 0;
        }

        @media(min-width:768px) {
            #page-wrapper {
                position: inherit;
                margin: 0 0 0 250px;
                padding: 52px 0 0 30px;
                /* for fixed menu*/
                /*padding: 0 30px;*/
                border-left: 1px solid #e7e7e7;
            }
        }
    </style>
    @include('sweetalert::alert')
    @stack('styles')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        @include('layouts.sidebar')

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav style="height: 3rem; background-color: rgb(0, 118, 24, 0.5);"
                    class="navbar navbar-expand topbar mb-1 static-top shadow">
                    <span class="text-dark font-weight-bold d-none d-lg-block">Selamat Datang di Website Dinas
                        Lingkungan Hidup</span>

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <span class="text-dark font-weight-bold d-lg-none">DLH</span>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item  no-arrow" style="cursor: move">
                            <a style="cursor: default" class="nav-link" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-dark font-weight-bold small">
                                    {{ Auth::user()->petugas->name }}({{ Auth::user()->role }})
                                </span>
                            </a>

                        </li>

                        <li class="nav-item   mx-1">
                            <a class="nav-link " href="#" id="" role="button" data-toggle="modal"
                                data-target="#logoutModal" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-sign-out-alt fa-fw"></i>
                                Logout
                            </a>

                        </li>
                    </ul>

                </nav>
                <!-- End of Topbar -->

                @if (session('error'))
                    <div class="alert alert-danger">
                        <b>Opps!</b> {{ session('error') }}
                    </div>
                @endif

                <!-- Begin Page Content -->
                @yield('content')
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->



        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ url('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="{{ url('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ url('vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ url('js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

    <!-- Page level custom scripts -->
    <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ url('/vendor/selectize-bootstrap4/dist/css/selectize.bootstrap4.css') }}" rel="stylesheet">
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('/vendor/selectize-bootstrap4/dist/js/selectize.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const mediaQuery = window.matchMedia('(max-width: 768px)');
        mediaQuery.addListener(handleDeviceChange);

        function handleDeviceChange(e) {
            if (e.matches) {
                $('#page_top').addClass('sidebar_toggled')
                $('#accordionSidebar').addClass('toggled')
                $('#accordionSidebar').css('position', '')
                $('#content-wrapper').css('margin-left', '')
            } else {
                $('#accordionSidebar').css('position', 'fixed')
                $('#content-wrapper').css('margin-left', '14rem')
                $('#page_top').removeClass('sidebar_toggled')
                $('#accordionSidebar').removeClass('toggled')
            }
        }
        handleDeviceChange(mediaQuery)
    </script>
    @stack('scripts')
</body>

</html>
