@extends('layouts.base')
@section('title', 'Masuk')
@section('body')
<div class="authp">
  <a class="brand" href="{{ route('home') }}" style="left:32px;top:16px">Oddesey</a>
  <img class="dec" src="{{ asset('img/login-ml2.png') }}" alt="" style="left:0;top:30%;width:200px" onerror="this.remove()">
  <img class="dec" src="{{ asset('img/login-warna.png') }}" alt="" style="left:-30px;bottom:-20px;width:226px" onerror="this.remove()">
  <div class="formside">
    <form method="post" action="{{ route('login') }}">@csrf
      <h1>Welcome Back</h1><p class="sub">Masuk untuk melanjutkan petualangan belajarmu.</p>
      @if(session('ok'))<div class="flash">{{ session('ok') }}</div>@endif
      <div class="fld"><input id="email" type="email" name="email" placeholder="Email" aria-label="Email" value="{{ old('email') }}" required autofocus>@error('email')<div class="err">{{ $message }}</div>@enderror</div>
      <div class="fld"><input id="pw" type="password" name="password" placeholder="Password" aria-label="Password" required></div>
      <div class="meta"><label><input type="checkbox" name="remember"> Remember Me</label><span>Forgot Password?</span></div>
      <button class="btn full">Masuk</button>
      <p class="alt"><a href="{{ route('privacy') }}" style="font-weight:500;color:var(--gray)">Kebijakan Privasi</a><br>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></p>
    </form>
  </div>
  <div class="panel" style="background-image:url('{{ asset('img/auth-login.png') }}')">
    @include('partials.stars', ['seed' => 11, 'n' => 25])
    <img class="dec float" src="{{ asset('img/login-stt.png') }}" alt="" style="left:-138px;bottom:-8px;width:221px" onerror="this.remove()">
    <img class="dec float2" src="{{ asset('img/login-ml1.png') }}" alt="" style="left:-91px;top:0;width:155px" onerror="this.remove()">
  </div>
</div>
@endsection
