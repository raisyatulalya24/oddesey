@extends('layouts.app')
@section('title', 'Materi')
@section('content')
@php $list = request()->filled('q') || request()->filled('subject'); @endphp
<h1>Materi Pembelajaran</h1><p class="lead">Pilih Mata Pelajaran yang Ingin Kamu Pelajari!</p>
<form class="search" method="get" role="search"><span aria-hidden="true">🔍</span><input type="search" name="q" value="{{ request('q') }}" placeholder="Cari Materi" aria-label="Cari materi">@if(request('subject'))<input type="hidden" name="subject" value="{{ request('subject') }}">@endif</form>
@if(!$list)
<div class="scs">
@foreach($subjects as $s)
  @php $done = $doneBy[$s->id] ?? 0; $pc = $s->materials_count ? round($done / $s->materials_count * 100) : 0; @endphp
  <article class="sc">
    <div class="im">@include('partials.subjimg', ['s' => $s, 'size' => 231, 'kind' => 'card'])</div>
    <h3>{{ $s->name }}</h3><p>{{ $s->description }}</p><div class="small" style="margin-top:4px">📖 {{ $s->materials_count }} materi · 🧪 {{ $simBy[$s->id] ?? 0 }} simulasi</div>
    <div class="track" role="progressbar" aria-valuenow="{{ $pc }}" aria-valuemin="0" aria-valuemax="100"><i style="width:{{ $pc }}%"></i></div>
    <div class="pc">{{ $pc }}%</div>
    <div class="foot"><span title="Waktu aktifmu belajar di bidang ini">⏱ {{ \App\Support\Fmt::duration($timeBy[$s->id] ?? 0) }}</span><a class="go" href="{{ route('materials.index', ['subject' => $s->slug]) }}" aria-label="Buka materi {{ $s->name }}"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M4 12h16M14 6l6 6-6 6"/></svg></a></div>
  </article>
@endforeach
</div>
@else
<div class="filters"><a class="pill" href="{{ route('materials.index') }}">← Semua bidang</a>@foreach($subjects as $s)<a class="pill {{ request('subject') === $s->slug ? 'on' : '' }}" href="{{ route('materials.index', ['subject' => $s->slug, 'q' => request('q')]) }}">{{ $s->name }}</a>@endforeach</div>
<div class="grid g3">
@forelse($materials as $m)
  <article class="card mcard">
    @auth<form method="post" action="{{ route('materials.bookmark', $m) }}">@csrf<button class="bm {{ in_array($m->id, $marked) ? 'on' : '' }}" aria-label="Bookmark" title="Bookmark">★</button></form>@endauth
    <div style="height:120px;display:grid;place-items:center">@include('partials.subjimg', ['s' => $m->subject, 'size' => 110])</div>
    <span class="chip" style="align-self:flex-start">{{ $m->subject->name }} · {{ ucfirst($m->level) }}</span>
    <h3>{{ $m->title }}</h3><p>{{ $m->summary }}</p>
    <div class="row between"><span class="small">{{ $m->simulations_count ? '🧪 ' . $m->simulations_count . ' simulasi' : '📖 Teks & visual' }}</span><a class="btn navy sm" href="{{ route('materials.show', $m) }}">Buka</a></div>
  </article>
@empty<div class="card">Materi tidak ditemukan.</div>@endforelse
</div>
<div style="margin-top:1.2rem">{{ $materials->links() }}</div>
@endif
@endsection
