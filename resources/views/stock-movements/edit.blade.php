@extends('layouts.app')
@section('title', 'Edit Transaksi')
@section('breadcrumb', 'Transaksi Stok / Edit')
@section('content')
<div class="page-heading"><a href="{{ route('stock-movements.index') }}" class="back-link">← Kembali ke transaksi</a><span class="eyebrow accent-eyebrow">AKTIVITAS GUDANG</span><h1>Edit transaksi <span class="heading-dot">.</span></h1><p>Perubahan transaksi akan dihitung ulang terhadap saldo stok.</p></div>
<section class="panel form-panel"><form method="POST" action="{{ route('stock-movements.update', $movement) }}">@csrf @method('PUT') @include('stock-movements.form')<div class="form-actions"><a href="{{ route('stock-movements.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan perubahan <span>→</span></button></div></form></section>
@endsection
