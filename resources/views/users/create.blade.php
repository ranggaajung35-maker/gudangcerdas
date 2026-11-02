@extends('layouts.app')
@section('title', 'Tambah Pengguna')
@section('breadcrumb', 'Pengguna / Tambah')
@section('content')
<div class="page-heading"><a href="{{ route('users.index') }}" class="back-link">← Kembali ke pengguna</a><span class="eyebrow accent-eyebrow">ADMINISTRASI</span><h1>Tambah pengguna <span class="heading-dot">.</span></h1><p>Buat akun baru dan tentukan hak aksesnya.</p></div>
<section class="panel form-panel"><form method="POST" action="{{ route('users.store') }}">@csrf @include('users.form')<div class="form-actions"><a href="{{ route('users.index') }}" class="btn btn-light">Batal</a><button class="btn btn-primary">Buat pengguna <span>→</span></button></div></form></section>
@endsection
