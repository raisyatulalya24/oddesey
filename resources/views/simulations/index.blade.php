@extends('layouts.app')
@section('title', 'Simulasi')
@section('content')
<h1>Simulation Lab</h1><p class="lead">{{ $sims->count() }} simulasi. Atur parameter, jalankan, dan amati hasilnya.</p>
<form class="search" method="get" role="search"><span aria-hidden="true">🔍</span><input type="search" name="q" value="{{ request('q') }}" placeholder="Cari simulasi" aria-label="Cari simulasi">@if(request('subject'))<input type="hidden" name="subject" value="{{ request('subject') }}">@endif</form>
<div class="filters"><a class="pill {{ request('subject') ? '' : 'on' }}" href="{{ route('simulations.index', ['q' => request('q')]) }}">Semua</a>
@foreach($subjects as $s)<a class="pill {{ request('subject') === $s->slug ? 'on' : '' }}" href="{{ route('simulations.index', ['subject' => $s->slug, 'q' => request('q')]) }}">{{ $s->name }}</a>@endforeach</div>
<div class="grid g3">@forelse($sims as $s)
<article class="card mcard"><div style="height:110px;display:grid;place-items:center">@include('partials.subjimg', ['s' => $s->material->subject, 'size' => 100])</div><span class="chip" style="align-self:flex-start">{{ $s->material->subject->name }}</span><h3>{{ $s->title }}</h3><p>{{ $s->description }}</p><a class="btn navy sm" style="align-self:flex-start" href="{{ route('simulations.show', $s) }}">Buka Lab →</a></article>
@empty<div class="card">Simulasi tidak ditemukan.</div>@endforelse</div>
@endsection
