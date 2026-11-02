@extends('layouts.app')
@section('title', 'Edit Pemasok')
@section('breadcrumb', 'Pemasok / Edit')
@section('content')
<div class="page-heading"><a href="{{ route('suppliers.index') }}" class="back-link">← Kembali ke pemasok</a><span class="eyebrow accent-eyebrow">MITRA BISNIS</span><h1>Edit pemasok <span class="heading-dot">.</span></h1><p>Perbarui informasi {{ $supplier->company_name }}.</p></div>
<section class="panel form-panel"><form method="POST" action="{{ route('suppliers.update', $supplier) }}">@csrf @method('PUT') @include('suppliers.form')<div class="form-actions"><a href="{{ route('suppliers.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan perubahan <span>→</span></button></div></form></section>
@endsection
