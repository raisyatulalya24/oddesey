@php
  $f = ($kind ?? 'icon') === 'card' && file_exists(public_path("img/card-{$s->slug}.png")) ? "card-{$s->slug}.png" : "subject-{$s->slug}.png";
@endphp
@if(file_exists(public_path("img/$f")))<img src="{{ asset("img/$f") }}" alt="{{ $s->name }}" style="width:{{ $size ?? 120 }}px;height:auto;max-height:{{ $size ?? 120 }}px;object-fit:contain" class="float">
@else @include('partials.icon', ['n' => $s->icon, 's' => ($size ?? 120) * .7]) @endif
