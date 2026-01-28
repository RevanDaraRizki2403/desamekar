<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Desa Sirnamekar - Login</title>

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

        /* PERUBAHAN: max-width ditingkatkan agar terlihat lebih besar */
        .container {
            max-width: 550px; 
        }

        .card {
            border-radius: 15px; /* Lebih membulat agar modern */
            overflow: hidden;
        }

        /* PERUBAHAN: Ukuran logo diperbesar */
        .img-logo {
            width: 120px;
            margin-bottom: 20px;
        }

        .input-group-custom {
            position: relative;
            margin-bottom: 1.5rem; /* Jarak antar input diperlebar */
        }

        /* PERUBAHAN: Input lebih tinggi agar terlihat kokoh */
        .input-group-custom input {
            padding: 1.5rem 1rem !important;
            padding-right: 50px !important;
            border-radius: 10px !important;
            font-size: 1rem;
        }

        .input-group-append-custom {
            position: absolute;
            right: 0;
            top: 0;
            bottom: 0;
            width: 50px;
            background: #f8f9fc;
            border: 1px solid #d1d3e2;
            border-left: none;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 0 10px 10px 0;
            color: #858796;
        }

        /* PERUBAHAN: Tombol diperbesar */
        .btn-user {
            font-weight: bold;
            font-size: 1.2rem;
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

        /* Tambahan: Judul diperbesar */
        .login-title {
            font-size: 1.8rem;
            margin-top: 10px;
        }
    </style>
</head>

<body class="bg-gradient-primary">

@error('email')
    <script>
        Swal.fire({
            title: "Terjadi Kesalahan",
            text: "{{ $message }}",
            icon: "error"
        });
    </script>
@enderror    
   
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg">
            <div class="card-body p-0">
                <div class="p-5">
                    <div class="text-center">
                        <img src="{{ asset('templet/img/logo_desa.png') }}" class="img-logo" alt="Logo Desa">
                        <h1 class="text-gray-900 font-weight-bold login-title">Sign In</h1>
                        <p class="text-gray-600 mb-4">Sistem Informasi Desa Sirnamekar</p>
                        <hr class="mb-4">
                    </div>
                   
                    <form class="user" action="{{ route('login.post') }}" method="POST">
                        @csrf
                       
                        <div class="input-group-custom">
                            <input type="email" name="email" class="form-control" placeholder="Masukkan Email Anda" required>
                            <div class="input-group-append-custom">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>

                        <div class="input-group-custom">
                            <input type="password" name="password" class="form-control" placeholder="Masukkan Password Anda" required>
                            <div class="input-group-append-custom">
                                <i class="fas fa-lock"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-user btn-block shadow-sm">
                            Masuk Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a class="text-white-link" href="/register">Belum punya akun? Daftar di sini</a>
        </div>
    </div>

    <script src="{{ asset('templet/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('templet/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('templet/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('templet/js/sb-admin-2.min.js') }}"></script>
</body>
</html>