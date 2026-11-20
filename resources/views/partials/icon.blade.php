@php $s = $s ?? 56; @endphp
<svg viewBox="0 0 64 64" width="{{ $s }}" height="{{ $s }}" aria-hidden="true">
@switch($n)
@case('leaf')<g class="float"><path d="M12 52C8 28 24 10 54 10c2 30-14 46-36 42z" fill="url(#gG)"/><path d="M14 50C26 34 34 28 46 20" stroke="#fff" stroke-width="2.5" fill="none" stroke-linecap="round"/></g>@break
@case('atom')<g fill="none" stroke="url(#gB)" stroke-width="3"><ellipse class="spin" cx="32" cy="32" rx="27" ry="10"/><ellipse class="spin2" cx="32" cy="32" rx="27" ry="10" transform="rotate(60 32 32)"/><ellipse class="spin" cx="32" cy="32" rx="27" ry="10" transform="rotate(120 32 32)"/></g><circle cx="32" cy="32" r="5" fill="#6b5bd6"/>@break
@case('flask')<path d="M24 6h16v4h-3v18l14 24c2 4 0 6-4 6H17c-4 0-6-2-4-6l14-24V10h-3z" fill="url(#gP)"/><path d="M19 44h26l5 9H14z" fill="#fff" opacity=".35"/><circle class="bub" cx="30" cy="46" r="2.5" fill="#fff"/><circle class="bub" style="animation-delay:-2s" cx="37" cy="48" r="2" fill="#fff"/>@break
@case('planet')<g class="float"><ellipse cx="32" cy="34" rx="29" ry="8" transform="rotate(-18 32 34)" fill="none" stroke="#a99cf5" stroke-width="3"/><circle cx="32" cy="32" r="15" fill="url(#gP)"/><path d="M6 43C16 50 46 46 58 26" fill="none" stroke="#c9c0fa" stroke-width="3" stroke-linecap="round"/></g>@break
@case('calc')<rect x="12" y="6" width="40" height="52" rx="8" fill="url(#gB)"/><rect x="18" y="12" width="28" height="12" rx="3" fill="#fff" opacity=".85"/>@foreach([0,1,2] as $r)@foreach([0,1,2] as $c)<circle cx="{{ 22+$c*10 }}" cy="{{ 34+$r*9 }}" r="3" fill="#fff" opacity=".8"/>@endforeach @endforeach @break
@default<circle cx="32" cy="32" r="20" fill="url(#gP)"/>
@endswitch
</svg>
