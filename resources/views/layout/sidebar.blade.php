@php
$menus = [
    // 1: ROLE ADMIN FARMING
    1 => [
        'MENU UTAMA' => [
            (object)['title' => 'Dashboard', 'path' => 'admin/dashboard', 'icon' => 'fas fa-fw fa-tachometer-alt'],
        ],
        'SUMBER DATA' => [
            (object)['title' => 'Data Ternak', 'path' => 'data-ternak', 'icon' => 'fas fa-fw fa-paw'], // Diubah ke fa-paw agar ikon selalu muncul
            (object)['title' => 'Data Pemeriksaan', 'path' => 'data-pemeriksaan', 'icon' => 'fas fa-fw fa-notes-medical'],
        ],
        'ANALISIS & PREDIKSI' => [
            (object)['title' => 'Prediksi Kelayakan & Harga', 'path' => 'prediksi-kelayakan', 'icon' => 'fas fa-fw fa-chart-line'],
            (object)['title' => 'Riwayat Prediksi', 'path' => 'riwayat-prediksi', 'icon' => 'fas fa-fw fa-history'],
            (object)['title' => 'Dataset & Evaluasi Model', 'path' => 'dataset-evaluasi', 'icon' => 'fas fa-fw fa-database'],
        ],
        'LAPORAN' => [
            (object)['title' => 'Laporan Data Ternak', 'path' => 'laporan-kelayakan', 'icon' => 'fas fa-fw fa-clipboard-list'],
        ],
    ],
    // 2: ROLE KARYAWAN FARM
    2 => [
        'MENU UTAMA' => [
            (object)['title' => 'Dashboard', 'path' => 'karyawan/dashboard', 'icon' => 'fas fa-fw fa-tachometer-alt'],
        ],
        'SUMBER DATA' => [
            (object)['title' => 'Data Ternak', 'path' => 'data-ternak', 'icon' => 'fas fa-fw fa-paw'],
            (object)['title' => 'Data Pemeriksaan', 'path' => 'data-pemeriksaan', 'icon' => 'fas fa-fw fa-notes-medical'],
        ],
    ],
    // 3: ROLE MANAGER
    3 => [
        'MENU UTAMA' => [
            (object)['title' => 'Dashboard', 'path' => 'manager/dashboard', 'icon' => 'fas fa-fw fa-tachometer-alt'],
        ],
        'ANALISIS & PREDIKSI' => [
            (object)['title' => 'Prediksi Kelayakan & Harga', 'path' => 'prediksi-kelayakan', 'icon' => 'fas fa-fw fa-chart-line'],
            (object)['title' => 'Riwayat Prediksi', 'path' => 'riwayat-prediksi', 'icon' => 'fas fa-fw fa-history'],
            (object)['title' => 'Dataset & Evaluasi Model', 'path' => 'dataset-evaluasi', 'icon' => 'fas fa-fw fa-database'],
        ],
        'LAPORAN' => [
            (object)['title' => 'Laporan Data Ternak', 'path' => 'laporan-kelayakan', 'icon' => 'fas fa-fw fa-clipboard-list'],
        ],
    ],
];
@endphp

<!-- CSS TAMPILAN SIDEBAR DENGAN TEMA MODERN & RAPI -->
<style>
    .custom-sidebar {
        background: linear-gradient(180deg, #0e6251 0%, #083b37 100%) !important;
        min-height: 100vh;
        box-shadow: 4px 0 10px rgba(0,0,0,0.05);
    }
    .sidebar-brand-box {
        padding: 1.2rem 1rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        text-decoration: none !important;
    }
    .custom-sidebar .sidebar-heading {
        color: rgba(255, 255, 255, 0.6) !important;
        font-size: 0.68rem !important;
        font-weight: 700 !important;
        letter-spacing: 1px;
        padding: 1rem 1.25rem 0.4rem 1.25rem !important;
        text-transform: uppercase;
    }
    .custom-sidebar .nav-item {
        margin: 0.2rem 0.75rem;
    }
    .custom-sidebar .nav-link {
        color: rgba(255, 255, 255, 0.82) !important;
        padding: 0.65rem 1rem !important;
        border-radius: 8px;
        display: flex !important;
        align-items: center;
        transition: all 0.2s ease-in-out;
        font-size: 0.88rem;
    }
    .custom-sidebar .nav-link i {
        width: 1.5rem;
        text-align: center;
        margin-right: 0.6rem;
        font-size: 0.95rem;
        opacity: 0.85;
    }
    .custom-sidebar .nav-link:hover {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.12);
        transform: translateX(3px);
    }
    .custom-sidebar .nav-item.active .nav-link {
        color: #ffffff !important;
        background: rgba(255, 255, 255, 0.22) !important;
        font-weight: 700 !important;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }
    .custom-sidebar .nav-item.active .nav-link i {
        opacity: 1;
        color: #2ecc71 !important; /* Warna highlight ikon saat aktif */
    }
    .sidebar-divider-custom {
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        margin: 0.75rem 0 0.25rem 0;
    }
</style>

<ul class="navbar-nav sidebar sidebar-dark accordion custom-sidebar" id="accordionSidebar">

    <!-- Brand Header -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center sidebar-brand-box" href="{{ url('/dashboard') }}">
        <div class="sidebar-brand-icon mr-2">
            <!-- Jika memiliki file logo gambar, aktifkan tag img di bawah ini -->
            <!-- <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" style="max-height: 38px; width: auto;"> -->
            <i class="fas fa-leaf text-success" style="font-size: 1.2rem;"></i>
        </div>
        <div class="sidebar-brand-text text-left">
            <div class="font-weight-bold text-white text-uppercase" style="font-size: 0.82rem; letter-spacing: 0.5px; line-height: 1.2;">
                PT. SUMBER MAKANAN SEHAT
            </div>
        </div>
    </a>

    @auth
        @if(isset($menus[auth()->user()->role_id]))
            @foreach ($menus[auth()->user()->role_id] as $heading => $group)
                <div class="sidebar-heading">
                    {{ $heading }}
                </div>

                @foreach ($group as $menu)
                    @php
                        $isActive = request()->is($menu->path) || request()->is($menu->path . '/*');
                    @endphp
                    <li class="nav-item {{ $isActive ? 'active' : '' }}">
                        <a class="nav-link" href="{{ url($menu->path) }}">
                            <i class="{{ $menu->icon }}"></i>
                            <span>{{ $menu->title }}</span>
                        </a>
                    </li>
                @endforeach
                
                <div class="sidebar-divider-custom"></div>
            @endforeach
        @endif
    @endauth

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline my-3">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>