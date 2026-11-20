@extends('layouts.base')
@section('body')
@php $on = fn($p) => request()->routeIs($p) ? 'on' : ''; @endphp
@auth
<div class="shell">
  <aside class="side">
    <a href="{{ route('home') }}" class="logo">@include('partials.logo')</a>
    <nav>
      <a class="{{ $on('dashboard') }}" href="{{ route('dashboard') }}">Dashboard</a>
      <a class="{{ $on('materials.*') }}" href="{{ route('materials.index') }}">Materi</a>
      <a class="{{ $on('simulations.*') }}" href="{{ route('simulations.index') }}">Simulasi</a>
      <a class="{{ $on('progress') }}" href="{{ route('progress') }}">Progress</a>
      <a class="{{ $on('profile') }}" href="{{ route('profile') }}">Profile</a>
      @if(auth()->user()->isAdmin())<a class="{{ $on('admin.*') }}" href="{{ route('admin.dashboard') }}">Admin</a>@endif
    </nav>
    <form class="out" method="post" action="{{ route('logout') }}">@csrf<button>Keluar</button></form>
  </aside>
  <div class="main">
    @if(session('ok'))<div class="flash">{{ session('ok') }}</div>@endif
    @yield('content')
  </div>
</div>
@else
<header class="topnav">
  <a href="{{ route('home') }}" class="logo">@include('partials.logo')</a>
  <nav><a href="{{ route('home') }}">Home</a><a href="{{ route('materials.index') }}">Materi</a><a href="{{ route('simulations.index') }}">Simulasi</a></nav>
  <a class="pillb" href="{{ route('login') }}">Log in</a><a class="pillb w" href="{{ route('register') }}">Daftar</a>
</header>
<div class="guestmain">@if(session('ok'))<div class="flash">{{ session('ok') }}</div>@endif @yield('content')</div>
@endauth
@endsection
