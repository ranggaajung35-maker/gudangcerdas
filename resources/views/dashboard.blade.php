@extends('layouts.app')
@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')
@section('content')
<div class="page-heading heading-with-action">
    <div><span class="eyebrow accent-eyebrow">OVERVIEW</span><h1>Dashboard <span class="heading-dot">.</span></h1><p>Ringkasan aktivitas dan kondisi inventaris gudang Anda.</p></div>
    <a href="{{ route('stock-movements.create') }}" class="btn btn-primary"><span>＋</span> Catat transaksi</a>
</div>
<div class="stats-grid">
    <article class="stat-card stat-main"><div class="stat-top"><span>Total Produk</span><span class="stat-icon icon-violet">▦</span></div><div class="stat-value">{{ number_format($stats['products']) }}</div><div class="stat-foot"><span class="stat-note">Item terdaftar di gudang</span><a href="{{ route('products.index') }}">Lihat <b>↗</b></a></div></article>
    <article class="stat-card"><div class="stat-top"><span>Nilai Persediaan</span><span class="stat-icon icon-blue">Rp</span></div><div class="stat-value stat-currency">Rp {{ number_format($stats['inventory_value'], 0, ',', '.') }}</div><div class="stat-foot"><span class="stat-note">Berdasarkan harga beli</span><span class="tiny-pill neutral">Valuasi</span></div></article>
    <article class="stat-card"><div class="stat-top"><span>Stok Menipis</span><span class="stat-icon icon-orange">!</span></div><div class="stat-value">{{ number_format($stats['low_stock']) }}</div><div class="stat-foot"><span class="stat-note">Mencapai batas minimum</span><a href="{{ route('products.index', ['stock' => 'low']) }}">Periksa <b>↗</b></a></div></article>
    <article class="stat-card"><div class="stat-top"><span>Total Pemasok</span><span class="stat-icon icon-green">♧</span></div><div class="stat-value">{{ number_format($stats['suppliers']) }}</div><div class="stat-foot"><span class="stat-note">Mitra pemasok tersimpan</span><a href="{{ route('suppliers.index') }}">Lihat <b>↗</b></a></div></article>
</div>
<div class="dashboard-grid">
    <section class="panel chart-panel"><div class="panel-heading"><div><h2>Pergerakan stok</h2><p>Ringkasan kuantitas barang tahun {{ now()->year }}</p></div><span class="select-static">Tahun ini <span>⌄</span></span></div>
        @php($chartMax = max(1, $chart->max(fn($m) => max($m['in'], $m['out']))))
        <div class="chart-legend"><span><i class="legend-dot legend-in"></i>Stok masuk</span><span><i class="legend-dot legend-out"></i>Stok keluar</span></div>
        <div class="bar-chart">@foreach($chart as $month)<div class="bar-group"><div class="bar-pair"><div class="bar bar-in" style="height: {{ max(3, ($month['in'] / $chartMax) * 140) }}px" title="Masuk: {{ $month['in'] }}"></div><div class="bar bar-out" style="height: {{ max(3, ($month['out'] / $chartMax) * 140) }}px" title="Keluar: {{ $month['out'] }}"></div></div><span>{{ $month['label'] }}</span></div>@endforeach</div>
        <div class="chart-summary"><div><span class="summary-mark in-mark">↗</span><span><small>Barang masuk bulan ini</small><strong>{{ number_format($stats['stock_in_month']) }} <em>unit</em></strong></span></div><div><span class="summary-mark out-mark">↘</span><span><small>Barang keluar bulan ini</small><strong>{{ number_format($stats['stock_out_month']) }} <em>unit</em></strong></span></div></div>
    </section>
    <section class="panel low-stock-panel"><div class="panel-heading"><div><h2>Perlu restock</h2><p>Produk pada atau di bawah stok minimum</p></div><a class="text-link" href="{{ route('products.index', ['stock' => 'low']) }}">Lihat semua →</a></div>
        @forelse($lowStockProducts as $product)<div class="stock-alert-row"><div class="product-symbol">{{ strtoupper(substr($product->name, 0, 1)) }}</div><div class="stock-product"><strong>{{ $product->name }}</strong><small>{{ $product->sku }} · {{ $product->category->name }}</small></div><div class="stock-quantity"><strong>{{ $product->stock }} {{ $product->unit }}</strong><small>Min. {{ $product->min_stock }}</small></div><span class="warning-dot"></span></div>@empty<div class="empty-state compact"><div class="empty-illustration">✓</div><strong>Stok terkendali</strong><p>Tidak ada produk yang perlu direstock.</p></div>@endforelse
    </section>
</div>
<section class="panel table-panel"><div class="panel-heading"><div><h2>Aktivitas terbaru</h2><p>Riwayat mutasi stok terakhir di gudang</p></div><a class="text-link" href="{{ route('stock-movements.index') }}">Semua transaksi →</a></div>
    <div class="table-wrap"><table><thead><tr><th>PRODUK</th><th>REFERENSI</th><th>JENIS</th><th>JUMLAH</th><th>PETUGAS</th><th>WAKTU</th></tr></thead><tbody>
    @forelse($recentMovements as $movement)<tr><td><div class="table-product"><span class="table-product-icon">▦</span><span><strong>{{ $movement->product->name }}</strong><small>{{ $movement->product->sku }}</small></span></div></td><td>{{ $movement->reference ?: '—' }}</td><td><span class="badge {{ $movement->type === 'in' ? 'badge-success' : 'badge-warning' }}">{{ $movement->type === 'in' ? 'Masuk' : 'Keluar' }}</span></td><td class="quantity-cell {{ $movement->type === 'in' ? 'text-green' : 'text-orange' }}">{{ $movement->type === 'in' ? '+' : '−' }}{{ number_format($movement->quantity) }} {{ $movement->product->unit }}</td><td>{{ $movement->user->name }}</td><td><span class="date-cell">{{ $movement->movement_date->format('d M Y') }}</span></td></tr>@empty<tr><td colspan="6"><div class="empty-table">Belum ada aktivitas stok. <a href="{{ route('stock-movements.create') }}">Catat transaksi pertama</a>.</div></td></tr>@endforelse
    </tbody></table></div>
</section>
@endsection
