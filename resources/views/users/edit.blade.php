@extends('layouts.app')
@section('title', 'Edit Pengguna')
@section('breadcrumb', 'Pengguna / Edit')
@section('content')
<div class="page-heading"><a href="{{ route('users.index') }}" class="back-link">← Kembali ke pengguna</a><span class="eyebrow accent-eyebrow">ADMINISTRASI</span><h1>Edit pengguna <span class="heading-dot">.</span></h1><p>Perbarui profil dan hak akses {{ $account->name }}.</p></div>
<section class="panel form-panel"><form method="POST" action="{{ route('users.update', $account) }}">@csrf @method('PUT') @include('users.form')<div class="form-actions"><a href="{{ route('users.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Simpan perubahan <span>→</span></button></div></form></section>
@endsection
