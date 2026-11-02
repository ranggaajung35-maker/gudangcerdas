@extends('layouts.app')
@section('title', 'Edit Kategori')
@section('breadcrumb', 'Kategori / Edit')
@section('content')
<div class="page-heading"><a href="{{ route('categories.index') }}" class="back-link">← Kembali ke kategori</a><span class="eyebrow accent-eyebrow">MASTER DATA</span><h1>Edit kategori <span class="heading-dot">.</span></h1><p>Perbarui informasi kategori {{ $category->name }}.</p></div>
<section class="panel form-panel"><form method="POST" action="{{ route('categories.update', $category) }}">@csrf @method('PUT') @include('categories.form')<div class="form-actions"><a href="{{ route('categories.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan perubahan <span>→</span></button></div></form></section>
@endsection
