@php
$menus = [
    // ROLE ADMIN
    1 => [
        'MENU UTAMA' => [
            (object)['title' => 'Dashboard', 'path' => 'dashboard', 'icon' => 'fas fa-fw fa-tachometer-alt'],
        ],
        'MANAJEMEN DATA' => [
            (object)['title' => 'Data Penduduk', 'path' => 'resident', 'icon' => 'fas fa-fw fa-users'],
            (object)['title' => 'Daftar Akun', 'path' => 'account-list', 'icon' => 'fas fa-fw fa-user-check'],
            (object)['title' => 'Permintaan Akun', 'path' => 'account-request', 'icon' => 'fas fa-fw fa-user-plus'],
        ],
        'PELAYANAN' => [
            (object)['title' => 'Aduan Warga', 'path' => 'Aduan', 'icon' => 'fas fa-fw fa-bullhorn'],
            (object)['title' => 'Pengajuan Masuk', 'path' => 'pengajuan-masuk', 'icon' => 'fas fa-fw fa-envelope-open-text'],
        ],
    ],
    // ROLE WARGA
    2 => [
        'MENU UTAMA' => [
            (object)['title' => 'Dashboard', 'path' => 'dashboard', 'icon' => 'fas fa-fw fa-tachometer-alt'],
        ],
        'LAYANAN MANDIRI' => [
            (object)['title' => 'Pengaduan', 'path' => 'Aduan', 'icon' => 'fas fa-fw fa-comments'],
            (object)['title' => 'Pengajuan Dokumen', 'path' => 'Ajuan', 'icon' => 'fas fa-fw fa-file-signature'],
        ],
    ],
];
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
        <div class="sidebar-brand">
        </div>
        <div class="sidebar-brand-text mx-3">DESA <sup>SIRNAMEKAR</sup></div>
    </a>

    <hr class="sidebar-divider my-0">

    @auth
        @foreach ($menus[auth()->user()->role_id] as $heading => $group)
            <div class="sidebar-heading mt-3">
                {{ $heading }}
            </div>

            @foreach ($group as $menu)
                <li class="nav-item {{ request()->is($menu->path .'*') ? 'active' : '' }}">
                    <a class="nav-link py-2" href="/{{ $menu->path }}">
                        <i class="{{ $menu->icon }}"></i>
                        <span>{{ $menu->title }}</span>
                    </a>
                </li>
            @endforeach
            
            <hr class="sidebar-divider">
        @endforeach
    @endauth

    <div class="text-center d-none d-md-inline mt-4">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>