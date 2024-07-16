<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>DLHK | Register</title>
    <!-- Favicons -->
    <link href="{{ asset('/img/favicon.ico') }}" rel="icon">
    <link href="{{ asset('/img/apple-touch-icon.png') }}" rel="apple-touch-icon">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
        }

        .box-left {
            background-color: rgb(7, 118, 24, 0.7) !important;
            position: relative;
        }

        .box-left img {
            width: 400px;
            height: 400px;
        }

        @media (max-width: 768px) {
            .box-left img {
                width: 200px;
                height: 200px;
            }
        }
    </style>
</head>

<body>
    <div class="w-100 vh-100 m-0 p-0 o-hidden">
        <div class="row h-100">
            <div class="col-xl-6 box-left d-flex justify-content-center align-items-center">
                <img src="{{ asset('img/dlh.png') }}" alt="DLHK">
            </div>
            <div class="col-xl-6 bg-white d-flex justify-content-center align-items-center">
                <div class="col-md-6 col-8">
                    <div class="text-center">
                        <h1 class="h4 text-primary font-weight-bold mb-4">Login DLHK</h1>
                    </div>
                    @if (session('success'))
                        <div class="alert alert-success">
                            <b>Success!</b> {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            <b>Opps!</b> {{ session('error') }}
                        </div>
                    @endif

                    <form id="registerForm" class="user" action="{{ url('register-admin') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input required name="username" class="form-control form-control-user"
                                id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Username...">
                        </div>
                        <div class="form-group">
                            <input required name="email" type="email" placeholder="Email...."
                                class="form-control form-control-user">
                        </div>
                        <div class="form-group">
                            <input required type="password" name="password" class="form-control form-control-user"
                                id="Pass" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <input required type="password" class="form-control form-control-user" id="confirmpass"
                                placeholder="Confirm Password">
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox small">
                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                <label class="custom-control-label" for="customCheck">Show Password</label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-user btn-block">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <script>
        const showPass = document.getElementById('customCheck');
        const pass = document.getElementById('Pass');
        const confirmpass = document.getElementById('confirmpass');
        showPass.addEventListener('change', function() {
            if (this.checked) {
                pass.type = 'text';
                confirmpass.type = 'text';
            } else {
                pass.type = 'password';
                confirmpass.type = 'password';
            }
        });
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const pass = document.getElementById('Pass').value;
            const confirmpass = document.getElementById('confirmpass').value;
            if (pass !== confirmpass) {
                alert('Password not match');
            } else {
                this.submit();
            }
        });
    </script>
</body>

</html>
