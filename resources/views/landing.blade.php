@extends('layouts.base')
@section('body')
@php $desc = ['biologi' => 'Eksplorasi Makhluk Hidup', 'fisika' => 'Hukum Alam Semesta', 'kimia' => 'Reaksi & Molekul', 'astronomi' => 'Misteri Luar Angkasa', 'matematika' => 'Logika & Angka']; @endphp
<div class="heroW">
  @include('partials.stars', ['seed' => 3, 'n' => 70])
  <header class="nav">
    <a href="{{ route('home') }}" class="logo">@include('partials.logo')</a>
    <nav><a href="{{ route('home') }}">Home</a><a href="{{ route('materials.index') }}">Materi</a><a href="{{ route('simulations.index') }}">Simulasi</a>@auth<a href="{{ route('progress') }}">Progress</a>@endauth</nav>
    @auth <a class="pillb" href="{{ route('dashboard') }}">Dashboard</a>
    @else <a class="pillb" href="{{ route('login') }}">Log in</a><a class="pillb w" href="{{ route('register') }}">Daftar</a> @endauth
  </header>
  <section class="hero">
    <div>
      <div class="tag">🚀 Your Journey Starts Here</div>
      <h1>Explore. Learn. Discover.</h1>
      <p>An interactive learning platform for a smarter, brighter future</p>
      <div class="btns"><a class="btn" href="{{ route('materials.index') }}">Mulai Belajar</a><a class="btn" style="background:#fff;color:var(--navy2)" href="#tentang">Pelajari Lebih Lanjut</a></div>
    </div>
    <div class="astro">
      <div class="orb"></div>
      @if(file_exists(public_path('img/astronaut.png')))<img src="{{ asset('img/astronaut.png') }}" alt="Astronot">
      @else
      <svg viewBox="0 0 460 380" width="100%" class="float" aria-hidden="true"><g filter="url(#glow)"><rect x="150" y="150" width="120" height="130" rx="46" fill="#f4f2ff"/><rect x="165" y="270" width="36" height="52" rx="16" fill="#e1ddf7"/><rect x="222" y="270" width="36" height="52" rx="16" fill="#e1ddf7"/><rect x="168" y="60" width="96" height="92" rx="44" fill="#f4f2ff"/><rect x="180" y="76" width="72" height="58" rx="28" fill="#1e2a78"/></g></svg>
      @endif
    </div>
  </section>
  <div class="glass">
    @foreach([['5 Bidang Ilmu', 'Eksplorasi materi', 'M4 5h7a3 3 0 013 3v11a2 2 0 00-2-2H4zM20 5h-6v13h6z'], ['Simulasi Virtual', 'Praktikum lab interaktif', 'M9 3h6M10 3v6L4 20h16l-6-11V3'], ['Refleksi & Eksperimen', 'Simpan hasil percobaanmu', 'M12 3l2.6 5.6 6 .7-4.4 4.1 1.2 6L12 16.5 6.6 19.4l1.2-6L3.4 9.3l6-.7z'], ['Progress Belajar', 'Pantau pencapaianmu', 'M4 20V10M10 20V4M16 20v-7M22 20H2']] as [$t, $s, $d])
    <div><i><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#1e2a78" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="{{ $d }}"/></svg></i><span>{{ $t }}<small>{{ $s }}</small></span></div>
    @endforeach
  </div>
  <img class="cloud l" src="{{ asset('img/cloud.png') }}" alt="" onerror="this.remove()">
  <img class="cloud r" src="{{ asset('img/cloud.png') }}" alt="" onerror="this.remove()">
</div>
<section class="arc">
  <h2>Jelajahi Dunia Pengetahuan</h2>
  <p class="sub">Pilih bidang ilmu yang ingin kamu pelajari</p>
  <div class="subjc">
    @foreach($subjects as $s)
    <a href="{{ route('materials.index', ['subject' => $s->slug]) }}"><div class="im">@include('partials.subjimg', ['s' => $s, 'size' => 130])</div><b>{{ $s->name }}</b><span>{{ $desc[$s->slug] ?? $s->description }}</span></a>
    @endforeach
  </div>
</section>
<section class="about" id="tentang">
  <div><h2>Tentang Oddesey</h2><p>Platform edukasi interaktif yang dirancang untuk membantu kamu belajar dengan cara yang lebih menarik, mudah, dan menyenangkan.</p><a class="btn" href="{{ route('register') }}">Pelajari Selengkapnya</a></div>
  @if(file_exists(public_path('img/about.png')))<img src="{{ asset('img/about.png') }}" alt="Observatorium di atas awan" class="float">
  @else<svg viewBox="0 0 420 240" aria-hidden="true"><ellipse cx="210" cy="220" rx="200" ry="26" fill="#d9d2fb"/><rect x="130" y="110" width="160" height="106" rx="10" fill="#f3f0ff" stroke="#c9c0fa" stroke-width="3"/><path d="M120 112a90 70 0 01180 0z" fill="url(#gP)"/></svg>@endif
</section>
<footer class="foot"><b>Oddesey</b><span>© {{ date('Y') }} Oddesey. All rights reserved.</span><span><a href="{{ route('privacy') }}">Privasi</a> &nbsp; <a href="{{ route('home') }}">Home</a></span></footer>
@endsection
