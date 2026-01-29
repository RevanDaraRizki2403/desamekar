<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Desa Sirnamekar - Login Profesional</title>

    <link href="{{ asset('templet/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700,800" rel="stylesheet">
    <link href="{{ asset('templet/css/sb-admin-2.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            /* Latar belakang menggunakan gradient yang lebih lembut dan modern */
            background: linear-gradient(135deg, #4e73df 0%, #224abe 100%) !important;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: 'Nunito', sans-serif;
        }

        /* Container dengan efek bayangan halus */
        .container {
            max-width: 450px;
            width: 90%;
        }

        /* Glassmorphism Effect pada Card */
        .card-login {
            border: none;
            border-radius: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .img-logo {
            width: 120px; /* Ukuran logo yang lebih proporsional */
            filter: drop-shadow(0 5px 10px rgba(0,0,0,0.1));
            transition: transform 0.3s ease;
        }

        .img-logo:hover {
            transform: scale(1.05);
        }

        .login-title {
            font-weight: 800;
            color: #4e73df;
            letter-spacing: 1px;
            margin-top: 15px;
        }

        /* Input Custom Style */
        .form-group-custom {
            position: relative;
            margin-bottom: 20px;
        }

        .form-control-user {
            height: 55px !important;
            padding: 10px 20px 10px 50px !important; /* Spasi untuk ikon di kiri */
            border-radius: 12px !important;
            border: 2px solid #eaecf4 !important;
            font-size: 0.95rem !important;
            transition: all 0.3s ease;
        }

        .form-control-user:focus {
            border-color: #4e73df !important;
            box-shadow: 0 0 15px rgba(78, 115, 223, 0.1) !important;
        }

        /* Ikon di dalam Input */
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

        /* Tombol Masuk yang Mewah */
        .btn-masuk {
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

        .btn-masuk:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(78, 115, 223, 0.3) !important;
        }

        .register-link {
            font-weight: 700;
            color: #fff;
            text-decoration: none;
            transition: all 0.3s;
            opacity: 0.8;
        }

        .register-link:hover {
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

@error('email')
    <script>
        Swal.fire({
            title: "Akses Ditolak",
            text: "{{ $message }}",
            icon: "error",
            confirmButtonColor: "#4e73df"
        });
    </script>
@enderror    
   
    <div class="container">
        <div class="card card-login shadow-lg">
            <div class="card-body p-0">
                <div class="p-5">
                    <div class="text-center mb-4">
                        <img src="{{ asset('templet/img/logo desaa.png') }}" class="img-logo" alt="Logo Desa">
                        <h2 class="login-title">SIRNAMEKAR</h2>
                        <p class="text-gray-600 small">Sistem Informasi Desa Digital</p>
                    </div>
                   
                    <form class="user" action="{{ route('login.post') }}" method="POST">
                        @csrf
                       
                        <div class="form-group-custom">
                            <input type="email" name="email" class="form-control form-control-user" placeholder="Alamat Email" required>
                            <i class="fas fa-envelope input-icon"></i>
                        </div>

                        <div class="form-group-custom">
                            <input type="password" name="password" class="form-control form-control-user" placeholder="Kata Sandi" required>
                            <i class="fas fa-lock input-icon"></i>
                        </div>

                        <button type="submit" class="btn btn-primary btn-masuk btn-block shadow">
                            Masuk Ke Sistem
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a class="register-link" href="/register">
                Belum punya akun? <span style="text-decoration: underline;">Daftar Sekarang</span>
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