@extends('layouts.app')
@section('title', 'Progress')
@section('content')
@php
  $W = 640; $H = 260; $pl = 52; $pr = 44; $pt = 20; $pb = 24; $n = count($series);
  $pts = []; foreach ($series as $i => $s) $pts[] = [$pl + ($W - $pl - $pr) * $i / ($n - 1), $pt + ($H - $pt - $pb) * (1 - $s['p'] / 100)];
  $d = 'M' . $pts[0][0] . ' ' . $pts[0][1];
  for ($i = 1; $i < $n; $i++) { $mx = ($pts[$i-1][0] + $pts[$i][0]) / 2; $d .= " C$mx {$pts[$i-1][1]} $mx {$pts[$i][1]} {$pts[$i][0]} {$pts[$i][1]}"; }
  $area = $d . " L{$pts[$n-1][0]} " . ($H - $pb) . " L{$pts[0][0]} " . ($H - $pb) . ' Z'; $last = $pts[$n - 1];
  $mn = ['', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
@endphp
<div class="row between wrap"><h1>Progres Belajarmu</h1><span class="chip" style="background:#d9d9d9;padding:.5rem 1.6rem;font-size:1rem">6 bulan terakhir →</span></div><br>
<div class="pg">
  <div class="chartc">
    <h2>Progres Belajarmu</h2><p class="small">Pantau perkembangan belajarmu setiap bulan</p>
    <svg viewBox="0 0 {{ $W }} {{ $H + 30 }}" width="100%" role="img" aria-label="Grafik progres {{ $series[$n-1]['p'] }}% bulan ini">
      <defs><linearGradient id="ag" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#7c6cf0" stop-opacity=".35"/><stop offset="1" stop-color="#7c6cf0" stop-opacity="0"/></linearGradient></defs>
      @foreach([0, 25, 50, 75, 100] as $g)@php $y = $pt + ($H - $pt - $pb) * (1 - $g / 100); @endphp
      <line x1="{{ $pl }}" x2="{{ $W - $pr }}" y1="{{ $y }}" y2="{{ $y }}" stroke="#d6d6e4" stroke-dasharray="4 5"/><text x="{{ $pl - 10 }}" y="{{ $y + 4 }}" text-anchor="end" font-size="13" fill="#555">{{ $g }}%</text>@endforeach
      <path d="{{ $area }}" fill="url(#ag)" class="fade"/>
      <path d="{{ $d }}" fill="none" stroke="#5b43b5" stroke-width="3.5" stroke-linecap="round" class="draw"/>
      @foreach($pts as $i => $p)<circle cx="{{ $p[0] }}" cy="{{ $p[1] }}" r="5.5" fill="#5b43b5" class="fade"/><text x="{{ $p[0] }}" y="{{ $H + 14 }}" text-anchor="middle" font-size="13" fill="#444">{{ $mn[(int) $series[$i]['m']] }}</text>@endforeach
      <g class="fade float2"><rect x="{{ $last[0] - 30 }}" y="{{ $last[1] - 38 }}" width="60" height="26" rx="13" fill="#5b43b5"/><text x="{{ $last[0] }}" y="{{ $last[1] - 20 }}" text-anchor="middle" font-size="13" font-weight="700" fill="#fff">{{ $series[$n-1]['p'] }}%</text></g>
    </svg>
  </div>
  <div class="hrs">
    @if(file_exists(public_path('img/stopwatch.png')))<img src="{{ asset('img/stopwatch.png') }}" width="110" alt="">@else
    <svg width="70" height="70" viewBox="0 0 64 64" class="float" aria-hidden="true"><circle cx="32" cy="36" r="22" fill="none" stroke="#5b43b5" stroke-width="5"/><path d="M32 22v14l9 6M26 6h12" stroke="#5b43b5" stroke-width="5" fill="none" stroke-linecap="round"/></svg>@endif
    <h3>Total Jam Belajar</h3><b>{{ \App\Support\Fmt::duration($allSec) }}</b>
    @if($delta !== null)<div class="{{ $delta >= 0 ? 'up' : 'down' }}">{{ $delta >= 0 ? '+' : '' }}{{ $delta }}% <span style="color:var(--navy)">dari bulan lalu</span></div>
    @else<div class="small">Bulan ini: <b style="display:inline;font-size:inherit">{{ \App\Support\Fmt::duration($cur) }}</b></div>@endif
    <p class="small" style="margin-top:.6rem">Dihitung dari waktu aktif saat kamu membuka materi dan simulasi.</p>
  </div>
</div>
<div class="pg" style="margin-top:2rem;grid-template-columns:1fr 407px">
  <div class="medals">
    @foreach($medals as $m)<div class="{{ $m['on'] ? '' : 'lock' }}"><svg width="96" height="110" viewBox="0 0 64 74" aria-label="{{ $m['name'] }}"><path d="M20 44l-6 26 12-6 6 8 4-26zM44 44l6 26-12-6-6 8-4-26z" fill="{{ $m['rib'] }}"/><circle cx="32" cy="30" r="26" fill="{{ $m['c1'] }}"/><circle cx="32" cy="30" r="20" fill="{{ $m['c2'] }}"/><path d="M32 15l4.4 9.2 10 1.3-7.4 6.9 1.9 10L32 37.5 23.1 42.4l1.9-10-7.4-6.9 10-1.3z" fill="{{ $m['c3'] }}"/></svg><br>{{ $m['name'] }}<br><span class="small">{{ $m['need'] }} lencana</span></div>@endforeach
  </div>
  <div class="cert">
    @if($certSubject)<h3>Sertifikat Penyelesaian</h3><h2>{{ $certSubject->name }} Dasar</h2><div><a class="btn navy sm" href="{{ route('certificate', $certSubject) }}" target="_blank">Unduh Sertifikat</a></div>
    @else<h3>Sertifikat Penyelesaian</h3><p class="small">Selesaikan semua materi di satu bidang ilmu untuk mendapatkan sertifikat.</p>@endif
  </div>
</div>
@endsection
