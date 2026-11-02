@extends('layouts.app')
@section('title', 'Tambah Kategori')
@section('breadcrumb', 'Kategori / Tambah')
@section('content')
<div class="page-heading"><a href="{{ route('categories.index') }}" class="back-link">← Kembali ke kategori</a><span class="eyebrow accent-eyebrow">MASTER DATA</span><h1>Tambah kategori <span class="heading-dot">.</span></h1><p>Buat kelompok baru untuk mengorganisasi produk.</p></div>
<section class="panel form-panel"><form method="POST" action="{{ route('categories.store') }}">@csrf @include('categories.form')<div class="form-actions"><a href="{{ route('categories.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan kategori <span>→</span></button></div></form></section>
@endsection
