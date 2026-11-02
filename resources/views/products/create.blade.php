@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('breadcrumb', 'Produk / Tambah')
@section('content')
<div class="page-heading"><a href="{{ route('products.index') }}" class="back-link">← Kembali ke produk</a><span class="eyebrow accent-eyebrow">INVENTARIS</span><h1>Tambah produk <span class="heading-dot">.</span></h1><p>Lengkapi informasi barang untuk mulai mengelola persediaan.</p></div>
<section class="panel form-panel"><form method="POST" action="{{ route('products.store') }}">@csrf @include('products.form')<div class="form-actions"><a href="{{ route('products.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan produk <span>→</span></button></div></form></section>
@endsection
