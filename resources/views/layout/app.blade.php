<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>SI PETERNAKAN - PT. Sumber Makanan Sehat</title>

    <link href="{{asset('templet/vendor/fontawesome-free/css/all.min.css')}}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <link href="{{asset('templet/css/sb-admin-2.min.css')}}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* Ubah warna background sidebar menjadi gradient login (Biru ke Hijau) */
        .bg-gradient-primary, 
        .sidebar {
            background-color: #2e59d9 !important;
            background-image: linear-gradient(180deg, #2e59d9 0%, #1cc88a 100%) !important;
            background-size: cover !important;
            width: 15rem !important; 
        }

        .sidebar .nav-item .nav-link span {
            font-size: 0.95rem; 
            font-weight: 700;
        }

        .sidebar .nav-item .nav-link i {
            font-size: 1.1rem;
            margin-right: 0.75rem;
        }

        .sidebar .sidebar-heading {
            font-size: 0.8rem;
            font-weight: 800;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.8) !important;
            padding-top: 1rem;
        }

        .card {
            border: none;
            border-radius: 12px;
            transition: transform 0.2s;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1) !important;
        }
        
        .card:hover {
            transform: translateY(-5px); 
        }

        .table td, .table th {
            font-size: 0.95rem;
            padding: 1rem;
            vertical-align: middle !important;
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        @include('layout.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layout.navbar')

                <div class="container-fluid">
                    <div class="pt-3">
                        @yield('content')
                    </div>
                </div>
            </div>

            @include('layout.footer')
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Modal Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('logout.post') }}" method="post"> 
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Logout?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Apakah anda yakin ingin keluar dari aplikasi?</div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Logout</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="{{asset('templet/vendor/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('templet/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('templet/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
    <script src="{{asset('templet/js/sb-admin-2.min.js')}}"></script>

    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 2500,
                showConfirmButton: false
            });
        @endif
    </script>
</body>
</html>