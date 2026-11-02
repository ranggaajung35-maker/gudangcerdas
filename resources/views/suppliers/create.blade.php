@extends('layouts.app')
@section('title', 'Tambah Pemasok')
@section('breadcrumb', 'Pemasok / Tambah')
@section('content')
<div class="page-heading"><a href="{{ route('suppliers.index') }}" class="back-link">← Kembali ke pemasok</a><span class="eyebrow accent-eyebrow">MITRA BISNIS</span><h1>Tambah pemasok <span class="heading-dot">.</span></h1><p>Masukkan informasi perusahaan dan narahubung pemasok.</p></div>
<section class="panel form-panel"><form method="POST" action="{{ route('suppliers.store') }}">@csrf @include('suppliers.form')<div class="form-actions"><a href="{{ route('suppliers.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan pemasok <span>→</span></button></div></form></section>
@endsection
