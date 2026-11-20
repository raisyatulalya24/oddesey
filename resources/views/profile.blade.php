@extends('layouts.app')
@section('title', 'Profil')
@section('content')
<h1>Profil Saya</h1><p class="lead">Perbarui data akunmu.</p>
<form class="card" style="max-width:520px" method="post" action="{{ route('profile.update') }}">@csrf @method('PUT')
@foreach([['name','Nama','text'],['email','Email','email'],['password','Password baru (opsional)','password'],['password_confirmation','Konfirmasi password baru','password']] as [$k,$l,$t])
<div class="fld"><label for="{{ $k }}">{{ $l }}</label><input id="{{ $k }}" type="{{ $t }}" name="{{ $k }}" value="{{ $t === 'password' ? '' : old($k, auth()->user()->$k) }}">@error($k)<div class="err">{{ $message }}</div>@enderror</div>
@endforeach
<button class="btn">Simpan Perubahan</button></form>
@endsection
