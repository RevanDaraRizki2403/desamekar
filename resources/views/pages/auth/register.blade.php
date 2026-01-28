<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Desa Sirnamekar - Register</title>

    <link href="{{ asset('templet/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="{{ asset('templet/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body.bg-gradient-primary {
            background: #3b5998 !important; 
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        /* Menyesuaikan lebar dengan halaman login baru */
        .container {
            max-width: 550px; 
        }

        .card {
            border-radius: 15px;
            overflow: hidden;
        }

        .img-logo {
            width: 100px;
            margin-bottom: 15px;
        }

        /* Form Group Styling */
        .form-group-custom {
            position: relative;
            margin-bottom: 1.2rem;
        }

        .form-group-custom input {
            padding: 1.5rem 1rem !important;
            border-radius: 10px !important;
            font-size: 1rem;
        }

        /* Tombol Simpan */
        .btn-user {
            font-weight: bold;
            font-size: 1.1rem;
            padding: 0.8rem !important;
            border-radius: 10px !important;
            transition: 0.3s;
        }

        .btn-user:hover {
            background-color: #2d4373 !important;
            transform: translateY(-2px);
        }

        .text-white-link {
            color: rgba(255,255,255,0.8) !important;
            text-decoration: none;
            font-size: 1rem;
        }

        .text-white-link:hover {
            color: #fff !important;
            text-decoration: underline;
        }

        .register-title {
            font-size: 1.6rem;
            margin-top: 10px;
        }
    </style>
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-0">
                <div class="p-5">
                    <div class="text-center">
                        <img src="{{ asset('templet/img/logo_desa.png') }}" class="img-logo" alt="Logo">
                        <h1 class="text-gray-900 font-weight-bold register-title">Pendaftaran Akun</h1>
                        <p class="text-gray-600 mb-4">Silakan isi data diri Anda untuk bergabung</p>
                        <hr class="mb-4">
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger shadow-sm border-0">
                            <ul class="mb-0 small">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <form class="user" action="{{ route('register') }}" method="POST">
                        @csrf
                        
                        <div class="form-group-custom">
                            <input type="text" class="form-control" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                        </div>

                        <div class="form-group-custom">
                            <input type="email" name="email" class="form-control" placeholder="Alamat Email" value="{{ old('email') }}" required>
                        </div>

                        <div class="form-group-custom">
                            <input type="password" name="password" class="form-control" placeholder="Kata Sandi Baru" required>
                        </div>

                        <button type="submit" class="btn btn-primary btn-user btn-block shadow-sm mt-4">
                            Daftar Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a class="text-white-link" href="/">Sudah punya akun? Masuk di sini</a>
        </div>
    </div>

    <script src="{{ asset('templet/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('templet/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('templet/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('templet/js/sb-admin-2.min.js') }}"></script>
</body>

</html>