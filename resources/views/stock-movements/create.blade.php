@extends('layouts.app')
@section('title', 'Catat Transaksi Stok')
@section('breadcrumb', 'Transaksi Stok / Tambah')
@section('content')
<div class="page-heading"><a href="{{ route('stock-movements.index') }}" class="back-link">← Kembali ke transaksi</a><span class="eyebrow accent-eyebrow">AKTIVITAS GUDANG</span><h1>Catat transaksi <span class="heading-dot">.</span></h1><p>Setiap transaksi akan langsung memperbarui saldo persediaan.</p></div>
<section class="panel form-panel"><form method="POST" action="{{ route('stock-movements.store') }}">@csrf @include('stock-movements.form')<div class="form-actions"><a href="{{ route('stock-movements.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan transaksi <span>→</span></button></div></form></section>
@endsection
