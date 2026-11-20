@extends('layouts.app')
@section('title', 'Simulasi')
@section('content')
<h1>Simulation Lab</h1><p class="lead">Atur parameter, jalankan, dan amati hasilnya.</p>
<div class="grid g3">@foreach($sims as $s)
<article class="card mcard"><div style="height:110px;display:grid;place-items:center">@include('partials.subjimg', ['s' => $s->material->subject, 'size' => 100])</div><span class="chip" style="align-self:flex-start">{{ $s->material->subject->name }}</span><h3>{{ $s->title }}</h3><p>{{ $s->description }}</p><a class="btn navy sm" style="align-self:flex-start" href="{{ route('simulations.show', $s) }}">Buka Lab →</a></article>
@endforeach</div>
@endsection
