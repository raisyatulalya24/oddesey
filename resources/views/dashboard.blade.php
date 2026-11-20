@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')
<div class="dtop">
  <div><h1>Halo, {{ auth()->user()->name }}!</h1><p class="lead">Terus semangat belajar, kamu hebat!</p></div>
  <div class="topright"><span class="bell" aria-hidden="true">🔔</span><div class="avatar" title="{{ auth()->user()->name }}"></div></div>
  @if(file_exists(public_path('img/orbit.png')))<img class="mascot" src="{{ asset('img/orbit.png') }}" alt="">@endif
</div>
<div class="gcard"><b>Progress Belajarmu</b>
  <div class="row" style="margin-top:22px;max-width:760px"><div class="pbar" role="progressbar" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"><i style="width:{{ $pct }}%"></i></div><span style="font-weight:500">{{ $pct }}%</span></div>
  <div class="small" style="margin-top:6px;color:#444">{{ $done }} dari {{ $total }} materi selesai</div></div>
<div class="dgrid">
  <div class="cont">
    <h2>continue learning</h2>
    @if($last)<p>{{ $last->material->subject->name }}: {{ $last->material->title }}</p>
    <a class="strip" href="{{ route('materials.show', $last->material) }}">lanjutkan</a>
    <div class="pic">@include('partials.subjimg', ['s' => $last->material->subject, 'size' => 120, 'kind' => 'card'])</div>
    @else<p>Belum ada riwayat belajar</p><a class="strip" href="{{ route('materials.index') }}">mulai jelajah</a>@endif
  </div>
  <div class="ach"><h2>Achievement</h2>
    <div class="in">
      @if(file_exists(public_path('img/medal.png')))<img src="{{ asset('img/medal.png') }}" alt="" width="130" class="float">
      @else<svg width="120" height="120" viewBox="0 0 64 64" class="float" aria-hidden="true"><circle cx="32" cy="30" r="20" fill="url(#gO)"/><path d="M32 18l3.5 7.5 8 1-6 5.5 1.7 8L32 36l-7.2 4 1.7-8-6-5.5 8-1z" fill="#fff"/><path d="M22 46l-4 14 8-4 4 6 2-14zM42 46l4 14-8-4-4 6-2-14z" fill="#7c6cf0"/></svg>@endif
      <b>{{ $badges }}</b><small>@if(file_exists(public_path('img/star.png')))<img src="{{ asset('img/star.png') }}" width="30" alt="">@else⭐@endif lencana</small>
    </div>
  </div>
</div>
<h2 class="sec2">My Exploration</h2>
<div class="tabs">@foreach(['riwayat' => 'Riwayat Materi', 'eksperimen' => 'Eksperimen', 'bookmark' => 'Bookmark'] as $k => $l)<a class="pill {{ $tab === $k ? 'on' : '' }}" href="{{ route('dashboard', ['tab' => $k]) }}">{{ $l }}</a>@endforeach</div>
<div class="card list" style="max-width:1000px">
@if($tab === 'riwayat')
  @forelse($histories as $h)<div>@include('partials.subjimg', ['s' => $h->material->subject, 'size' => 44])<div class="grow"><a href="{{ route('materials.show', $h->material) }}"><b>{{ $h->material->title }}</b></a><div class="small">{{ $h->material->subject->name }} · {{ $h->last_viewed_at?->diffForHumans() }}</div></div><span class="chip">{{ $h->status === 'completed' ? 'Selesai' : 'Dilihat' }}</span></div>@empty<p class="small">Belum ada riwayat.</p>@endforelse
@elseif($tab === 'eksperimen')
  @forelse($experiments as $e)<div style="align-items:flex-start"><div class="grow"><b>{{ $e->simulation->title }}</b><div class="small">{{ $e->created_at->format('d M Y H:i') }}</div>
    <div class="small">Parameter: @foreach($e->parameters_used as $k => $v){{ $k }} = {{ $v }}@if(!$loop->last); @endif @endforeach</div>
    <div class="small">Hasil: @foreach($e->result_data as $k => $v){{ $k }}: <b>{{ $v }}</b>@if(!$loop->last); @endif @endforeach</div>
    @foreach($e->answers as $a)<div class="small" style="margin-top:.3rem">💭 <i>{{ $a->question->question }}</i><br>{{ $a->answer }}</div>@endforeach</div>
    <form method="post" action="{{ route('experiments.destroy', $e) }}" onsubmit="return confirm('Hapus eksperimen ini?')">@csrf @method('DELETE')<button class="btn danger sm">Hapus</button></form></div>@empty<p class="small">Belum ada eksperimen. Jalankan simulasi lalu simpan hasilnya.</p>@endforelse
@else
  @forelse($bookmarks as $m)<div>@include('partials.subjimg', ['s' => $m->subject, 'size' => 44])<div class="grow"><a href="{{ route('materials.show', $m) }}"><b>{{ $m->title }}</b></a><div class="small">{{ $m->subject->name }}</div></div><form method="post" action="{{ route('materials.bookmark', $m) }}">@csrf<button class="btn ghost sm">Hapus bookmark</button></form></div>@empty<p class="small">Belum ada bookmark.</p>@endforelse
@endif
</div>
@endsection
