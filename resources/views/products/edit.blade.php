@extends('layouts.app')
@section('title', 'Edit Produk')
@section('breadcrumb', 'Produk / Edit')
@section('content')
<div class="page-heading"><a href="{{ route('products.index') }}" class="back-link">← Kembali ke produk</a><span class="eyebrow accent-eyebrow">INVENTARIS</span><h1>Edit produk <span class="heading-dot">.</span></h1><p>Perbarui informasi {{ $product->name }}. Stok dikelola melalui menu transaksi.</p></div>
<section class="panel form-panel"><form method="POST" action="{{ route('products.update', $product) }}">@csrf @method('PUT') @include('products.form')<div class="form-actions"><a href="{{ route('products.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan perubahan <span>→</span></button></div></form></section>
@endsection
