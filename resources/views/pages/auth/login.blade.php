<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SI PETERNAKAN PT. Sumber Makanan Sehat</title>
    
    <!-- SB Admin 2 / FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #2e59d9 0%, #1cc88a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: #ffffff;
            border-radius: 1.25rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            padding: 2.5rem 2rem;
            width: 100%;
            max-width: 420px;
        }

        .brand-icon {
            width: 70px;
            height: 70px;
            background: #e8f5e9;
            color: #1cc88a;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin: 0 auto 1rem;
        }

        .form-control {
            border-radius: 0.75rem;
            height: 48px;
            padding-left: 2.75rem;
            border: 1px solid #e3e6f0;
        }

        .form-control:focus {
            border-color: #1cc88a;
            box-shadow: 0 0 0 0.2rem rgba(28, 200, 138, 0.25);
        }

        .input-icon-wrapper {
            position: relative;
        }

        .input-icon-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #b7b9cc;
            z-index: 10;
        }

        .btn-login {
            background-color: #4e73df;
            border: none;
            border-radius: 0.75rem;
            height: 48px;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background-color: #2e59d9;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="login-card">
    <!-- Header / Logo -->
    <div class="text-center">
        <div class="brand-icon">
            <i class="fas fa-paw"></i>
        </div>
        <h4 class="font-weight-bold text-gray-900 mb-1" style="color: #2e59d9;">SI PETERNAKAN</h4>
        <p class="text-muted small mb-4">PT. Sumber Makanan Sehat</p>
    </div>

    <!-- Alert Notifikasi Error (Session & Validation) -->
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-left small" role="alert">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show text-left small" role="alert">
            <i class="fas fa-exclamation-circle mr-1"></i>
            <ul class="mb-0 pl-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Form Login -->
    <form action="{{ route('login.post') }}" method="POST">
        @csrf
        <div class="form-group mb-3 input-icon-wrapper">
            <i class="fas fa-envelope"></i>
            <input type="email" name="email" class="form-control" placeholder="Alamat Email" value="{{ old('email') }}" required autofocus>
        </div>

        <div class="form-group mb-4 input-icon-wrapper">
            <i class="fas fa-lock"></i>
            <input type="password" name="password" class="form-control" placeholder="Kata Sandi" required>
        </div>

        <button type="submit" class="btn btn-primary btn-block btn-login mb-3">
            Masuk Ke Sistem
        </button>
    </form>

    <!-- Footer Information -->
    <div class="text-center mt-3">
        <span class="small text-muted">Sistem Informasi Manajemen & Prediksi Kelayakan Ternak</span>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>