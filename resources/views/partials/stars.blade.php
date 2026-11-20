@php mt_srand($seed ?? 7); @endphp
<svg class="fx" preserveAspectRatio="xMidYMid slice" viewBox="0 0 1000 600" aria-hidden="true">
@for($i=0;$i<($n ?? 45);$i++)<circle class="tw" cx="{{ mt_rand(0,1000) }}" cy="{{ mt_rand(0,600) }}" r="{{ mt_rand(6,18)/10 }}" fill="#fff" style="animation-delay:-{{ mt_rand(0,30)/10 }}s"/>@endfor
@for($i=0;$i<4;$i++)<path class="tw" d="M{{ mt_rand(50,950) }} {{ mt_rand(30,500) }}l3 8 8 3-8 3-3 8-3-8-8-3 8-3z" fill="#cfc8ff" style="animation-delay:-{{ $i }}s"/>@endfor
</svg>
