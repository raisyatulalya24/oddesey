@extends('layouts.base')
@section('title', $sim->title)
@section('body')
<div class="simp">
  <div class="lft">
    @if(session('ok'))<div class="flash">{{ session('ok') }}</div>@endif
    <div class="head"><a href="{{ route('materials.show', $sim->material) }}" aria-label="Kembali ke materi">←</a><div>Simulasi Laboratorium</div></div>
    <div class="params">
      <h2>Atur Parameter:</h2>
      @foreach($sim->parameters as $p)
      <div class="slider"><div class="row"><label for="p-{{ $p->name }}">{{ $p->label }}</label><output id="o-{{ $p->name }}">{{ (float) $p->default_value }}{{ $p->unit }}</output></div>
        <small>{{ (float) $p->default_value }}{{ $p->unit }}</small>
        <input type="range" id="p-{{ $p->name }}" data-p="{{ $p->name }}" data-label="{{ $p->label }} ({{ $p->unit }})" data-def="{{ (float) $p->default_value }}" data-unit="{{ $p->unit }}" min="{{ (float) $p->min_value }}" max="{{ (float) $p->max_value }}" step="{{ (float) $p->step }}" value="{{ (float) $p->default_value }}"></div>
      @endforeach
    </div>
    <button class="runb" id="run">Jalankan Simulasi →</button>
    <div class="row" style="justify-content:center;margin-bottom:1rem"><button class="btn ghost sm" id="reset" type="button">Reset</button></div>
    <div id="resbox" hidden><h3>Hasil</h3><div class="results" id="results"></div>
      @auth<button class="btn" id="save" style="width:100%">💾 Simpan Hasil</button>@else<a class="btn" href="{{ route('login') }}" style="width:100%">Masuk untuk menyimpan hasil</a>@endauth</div>
  </div>
  <div class="rgt" style="background-image:url('{{ asset('img/sim-bg.png') }}')">
    <div class="simcv"><canvas id="cv" width="760" height="520" aria-label="Area simulasi"></canvas></div>
  </div>
</div>
@auth
<div class="modal" id="modal"><form class="card" method="post" action="{{ route('experiments.store') }}">@csrf
  <h3>Ringkasan & Refleksi</h3><div class="results" id="sum"></div>
  <input type="hidden" name="simulation_id" value="{{ $sim->id }}"><input type="hidden" name="parameters_used" id="f-par"><input type="hidden" name="result_data" id="f-res">
  <div style="margin-top:1rem">@forelse($sim->questions as $q)<div class="fld"><label for="q{{ $q->id }}">{{ $loop->iteration }}. {{ $q->question }}</label><textarea id="q{{ $q->id }}" name="answers[{{ $q->id }}]" rows="2"></textarea></div>@empty<p class="small">Tidak ada pertanyaan refleksi.</p>@endforelse</div>
  <div class="row"><button class="btn navy">Simpan</button><button class="btn ghost" type="button" id="cancel">Batal</button></div>
</form></div>
@endauth
@include('partials.tracker', ['material_id' => $sim->material_id])
@endsection
@push('scripts')
<script>window.SIM = @json(['type' => $sim->type]);</script>
<script src="{{ asset('js/sims-extra.js') }}"></script>
<script src="{{ asset('js/sim.js') }}"></script>
@endpush
