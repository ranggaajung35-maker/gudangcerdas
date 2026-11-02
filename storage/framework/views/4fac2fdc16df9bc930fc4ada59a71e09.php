<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> · GudangCerdas</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="app-body">
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <a href="<?php echo e(route('dashboard')); ?>" class="brand-lockup">
            <span class="brand-mark">G<span>+</span></span>
            <span class="brand-copy"><strong>GudangCerdas</strong><small>INVENTORY MANAGEMENT</small></span>
        </a>
        <div class="side-label">MENU UTAMA</div>
        <nav class="side-nav">
            <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>"><span class="nav-icon">◫</span>Dashboard</a>
            <a href="<?php echo e(route('products.index')); ?>" class="nav-link <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>"><span class="nav-icon">▦</span>Produk</a>
            <a href="<?php echo e(route('categories.index')); ?>" class="nav-link <?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>"><span class="nav-icon">▤</span>Kategori</a>
            <a href="<?php echo e(route('suppliers.index')); ?>" class="nav-link <?php echo e(request()->routeIs('suppliers.*') ? 'active' : ''); ?>"><span class="nav-icon">♧</span>Pemasok</a>
            <a href="<?php echo e(route('stock-movements.index')); ?>" class="nav-link <?php echo e(request()->routeIs('stock-movements.*') ? 'active' : ''); ?>"><span class="nav-icon">⇄</span>Transaksi Stok</a>
            <a href="<?php echo e(route('reports.index')); ?>" class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>"><span class="nav-icon">▥</span>Laporan</a>
        </nav>
        <?php if(auth()->user()->isAdmin()): ?>
            <div class="side-label side-label-spaced">ADMINISTRASI</div>
            <nav class="side-nav">
                <a href="<?php echo e(route('users.index')); ?>" class="nav-link <?php echo e(request()->routeIs('users.*') ? 'active' : ''); ?>"><span class="nav-icon">♙</span>Pengguna</a>
            </nav>
        <?php endif; ?>
        <div class="sidebar-bottom">
            <div class="help-card"><span class="help-spark">✦</span><strong>Kelola stok lebih rapi.</strong><p>Pantau persediaan dan mutasi barang dari satu tempat.</p><a href="<?php echo e(route('reports.index')); ?>">Lihat laporan <span>→</span></a></div>
            <div class="profile-mini">
                <div class="avatar"><?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?></div>
                <div class="profile-info"><strong><?php echo e(auth()->user()->name); ?></strong><small><?php echo e(auth()->user()->role === 'admin' ? 'Administrator' : 'Staff Gudang'); ?></small></div>
                <form method="POST" action="<?php echo e(route('logout')); ?>"><?php echo csrf_field(); ?><button class="icon-button logout-button" title="Keluar" aria-label="Keluar">↗</button></form>
            </div>
        </div>
    </aside>

    <main class="main-area">
        <header class="topbar">
            <div class="topbar-left"><button class="mobile-menu" id="mobileMenu" aria-label="Buka menu">☰</button><div><span class="eyebrow"><?php echo e(now()->translatedFormat('l, d F Y')); ?></span><div class="breadcrumb">Gudang / <strong><?php echo $__env->yieldContent('breadcrumb', 'Dashboard'); ?></strong></div></div></div>
            <div class="topbar-right"><span class="status-dot"></span><span class="online-label">Sistem aktif</span><div class="top-avatar"><?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?></div></div>
        </header>
        <div class="page-content">
            <?php echo $__env->make('partials.alerts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php echo $__env->yieldContent('content'); ?>
            <footer class="app-footer"><span>© <?php echo e(date('Y')); ?> GudangCerdas</span><span>Built for smarter inventory operations</span></footer>
        </div>
    </main>
</div>
<script src="<?php echo e(asset('js/app.js')); ?>" defer></script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\Users\AJUNG\Downloads\GudangCerdas-UAS\GudangCerdas\resources\views/layouts/app.blade.php ENDPATH**/ ?>