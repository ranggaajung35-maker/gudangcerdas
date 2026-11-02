<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') · GudangCerdas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('styles')
</head>
<body class="app-body">
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <a href="{{ route('dashboard') }}" class="brand-lockup">
            <span class="brand-mark">G<span>+</span></span>
            <span class="brand-copy"><strong>GudangCerdas</strong><small>INVENTORY MANAGEMENT</small></span>
        </a>
        <div class="side-label">MENU UTAMA</div>
        <nav class="side-nav">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><span class="nav-icon">◫</span>Dashboard</a>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}"><span class="nav-icon">▦</span>Produk</a>
            <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}"><span class="nav-icon">▤</span>Kategori</a>
            <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"><span class="nav-icon">♧</span>Pemasok</a>
            <a href="{{ route('stock-movements.index') }}" class="nav-link {{ request()->routeIs('stock-movements.*') ? 'active' : '' }}"><span class="nav-icon">⇄</span>Transaksi Stok</a>
            <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}"><span class="nav-icon">▥</span>Laporan</a>
        </nav>
        @if(auth()->user()->isAdmin())
            <div class="side-label side-label-spaced">ADMINISTRASI</div>
            <nav class="side-nav">
                <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"><span class="nav-icon">♙</span>Pengguna</a>
            </nav>
        @endif
        <div class="sidebar-bottom">
            <div class="help-card"><span class="help-spark">✦</span><strong>Kelola stok lebih rapi.</strong><p>Pantau persediaan dan mutasi barang dari satu tempat.</p><a href="{{ route('reports.index') }}">Lihat laporan <span>→</span></a></div>
            <div class="profile-mini">
                <div class="avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
                <div class="profile-info"><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->role === 'admin' ? 'Administrator' : 'Staff Gudang' }}</small></div>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="icon-button logout-button" title="Keluar" aria-label="Keluar">↗</button></form>
            </div>
        </div>
    </aside>

    <main class="main-area">
        <header class="topbar">
            <div class="topbar-left"><button class="mobile-menu" id="mobileMenu" aria-label="Buka menu">☰</button><div><span class="eyebrow">{{ now()->translatedFormat('l, d F Y') }}</span><div class="breadcrumb">Gudang / <strong>@yield('breadcrumb', 'Dashboard')</strong></div></div></div>
            <div class="topbar-right"><span class="status-dot"></span><span class="online-label">Sistem aktif</span><div class="top-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div></div>
        </header>
        <div class="page-content">
            @include('partials.alerts')
            @yield('content')
            <footer class="app-footer"><span>© {{ date('Y') }} GudangCerdas</span><span>Built for smarter inventory operations</span></footer>
        </div>
    </main>
</div>
<script src="{{ asset('js/app.js') }}" defer></script>
@stack('scripts')
</body>
</html>
