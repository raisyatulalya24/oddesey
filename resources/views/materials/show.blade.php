@extends('layouts.app')
@section('title', $material->title)
@section('content')
<div class="card row between wrap" style="margin-bottom:1.4rem;background:linear-gradient(120deg,#0b1437,#1e2a78);color:#fff;position:relative;overflow:hidden">
  @include('partials.stars', ['seed' => 5, 'n' => 20])
  <div style="position:relative"><span class="chip">{{ $material->subject->name }} · {{ ucfirst($material->level) }}</span><h1 style="margin-top:.5rem">{{ $material->title }}</h1><p style="opacity:.8">{{ $material->summary }}</p></div>
  <div style="position:relative">@include('partials.subjimg', ['s' => $material->subject, 'size' => 120])</div>
</div>
<div class="room">
  <article class="card prose">@foreach(preg_split("/\n{2,}/", $material->content) as $p)<p>{!! nl2br(e($p)) !!}</p>@endforeach
    <svg viewBox="0 0 520 90" width="100%" aria-hidden="true"><path class="draw" d="M0 45 Q 32 0 65 45 T 130 45 T 195 45 T 260 45 T 325 45 T 390 45 T 455 45 T 520 45" fill="none" stroke="url(#gP)" stroke-width="4" stroke-linecap="round"/></svg>
  </article>
  <aside class="card" style="align-self:start"><h3>Aksi</h3><div class="grid" style="margin-top:.8rem;gap:.7rem">
    @foreach($material->simulations->where('is_active', true) as $s)<a class="btn navy" href="{{ route('simulations.show', $s) }}">🧪 {{ $s->title }}</a>@endforeach
    @auth
      @unless($material->simulation)
        @if($done)<span class="chip" style="text-align:center">✓ Materi selesai</span>
        @else<form method="post" action="{{ route('materials.complete', $material) }}">@csrf<button class="btn navy" style="width:100%">✓ Tandai selesai</button></form>@endif
      @endunless
      <form method="post" action="{{ route('materials.bookmark', $material) }}">@csrf<button class="btn ghost" style="width:100%">{{ $marked ? '★ Hapus bookmark' : '☆ Bookmark materi' }}</button></form>
    @else<a class="btn ghost" href="{{ route('login') }}">Masuk untuk bookmark</a>@endauth
    <a class="btn ghost" href="{{ route('materials.index', ['subject' => $material->subject->slug]) }}">← Materi {{ $material->subject->name }}</a>
  </div></aside>
</div>
@include('partials.tracker', ['material_id' => $material->id])
@endsection
