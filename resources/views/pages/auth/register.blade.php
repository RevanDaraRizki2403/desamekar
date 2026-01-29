<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Desa Sirnamekar - Daftar Akun</title>

    <link href="{{ asset('templet/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800" rel="stylesheet">
    <link href="{{ asset('templet/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: 'Nunito', sans-serif;
        }

        /* Container disamakan 450px seperti Login */
        .container {
            max-width: 450px;
            width: 90%;
        }

        /* Glassmorphism Effect disamakan */
        .card-register {
            border: none;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .img-logo {
            width: 120px;
            filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));
            transition: transform 0.3s ease;
        }

        .register-title {
            font-weight: 800;
            color: #4e73df;
            letter-spacing: 1px;
            margin-top: 15px;
        }

        .form-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        /* Tinggi Input disamakan 55px */
        .form-control-user {
            height: 55px !important;
            padding: 10px 20px 10px 50px !important;
            border-radius: 12px !important;
            border: 2px solid #eaecf4 !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease;
        }

        .form-control-user:focus {
            border-color: #4e73df !important;
            box-shadow: 0 0 15px rgba(78, 115, 223, 0.1) !important;
        }

        .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #d1d3e2;
            z-index: 10;
            transition: color 0.3s ease;
        }

        .form-control-user:focus + .input-icon {
            color: #4e73df;
        }

        /* Tombol disamakan tinggi dan warnanya */
        .btn-daftar {
            background: linear-gradient(to right, #4e73df, #224abe) !important;
            border: none !important;
            height: 55px;
            border-radius: 12px !important;
            font-weight: 700 !important;
            font-size: 1rem !important;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: all 0.3s ease !important;
        }

        .btn-daftar:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(78, 115, 223, 0.3) !important;
        }

        .login-link {
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s;
            opacity: 0.8;
        }

        .login-link:hover {
            opacity: 1;
            text-decoration: none;
            color: #f8f9fc;
        }

        .footer-text {
            color: rgba(255,255,255,0.6);
            font-size: 0.8rem;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card card-register shadow-lg">
            <div class="card-body p-0">
                <div class="p-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('templet/img/logo desaa.png') }}" class="img-logo" alt="Logo Desa">
                        <h2 class="register-title">DAFTAR AKUN</h2>
                        <p class="text-gray-600 small">Silakan lengkapi data diri Anda</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 px-3 border-0 small mb-4" style="border-radius: 10px;">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                   
                    <form class="user" action="{{ route('register') }}" method="POST">
                        @csrf
                       
                        <div class="form-group-custom">
                            <input type="text" name="name" class="form-control form-control-user" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
                            <i class="fas fa-user input-icon"></i>
                        </div>

                        <div class="form-group-custom">
                            <input type="email" name="email" class="form-control form-control-user" placeholder="Alamat Email" value="{{ old('email') }}" required>
                            <i class="fas fa-envelope input-icon"></i>
                        </div>

                        <div class="form-group-custom">
                            <input type="password" name="password" class="form-control form-control-user" placeholder="Kata Sandi" required>
                            <i class="fas fa-lock input-icon"></i>
                        </div>

                        <button type="submit" class="btn btn-primary btn-daftar btn-block shadow">
                            Daftar Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a class="login-link" href="/">
                Sudah punya akun? <span style="text-decoration: underline;">Masuk di sini</span>
            </a>
            <div class="footer-text">
                 2026 Desa Sirnamekar
            </div>
        </div>
    </div>

    <script src="{{ asset('templet/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('templet/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('templet/js/sb-admin-2.min.js') }}"></script>
</body>

</html>