@extends('layouts.base')
@section('title', 'Daftar')
@section('body')
<div class="authp">
  <a class="brand" href="{{ route('home') }}" style="left:32px;top:16px">Oddesey</a>
  <img class="dec float" src="{{ asset('img/reg-kl.png') }}" alt="" style="left:75px;top:205px;width:184px" onerror="this.remove()">
  <div class="formside">
    <form method="post" action="{{ route('register') }}">@csrf
      <h1>Buat Akun Baru</h1><p class="sub">Mari mulai petualanganmu bersama Oddesey!</p>
      @foreach([['name','Nama Lengkap','text'],['email','Email Address','email'],['password','Password','password'],['password_confirmation','Konfirmasi Password','password']] as [$k,$l,$t])
      <div class="fld"><input id="{{ $k }}" type="{{ $t }}" name="{{ $k }}" placeholder="{{ $l }}" aria-label="{{ $l }}" value="{{ $t === 'password' ? '' : old($k) }}" required>@error($k)<div class="err">{{ $message }}</div>@enderror</div>
      @endforeach
      <button class="btn full">Daftar</button>
      <p class="alt">Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
    </form>
  </div>
  <div class="panel" style="background-image:url('{{ asset('img/auth-register.png') }}')">
    @include('partials.stars', ['seed' => 21, 'n' => 30])
    <img class="dec float2" src="{{ asset('img/reg-kbs.png') }}" alt="" style="left:-139px;top:0;width:237px" onerror="this.remove()">
    <img class="dec float" src="{{ asset('img/reg-kc.png') }}" alt="" style="left:-120px;bottom:-36px;width:197px" onerror="this.remove()">
  </div>
</div>
@endsection
