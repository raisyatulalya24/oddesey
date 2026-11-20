<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
@switch($n)
@case('home')<path d="M3 11l9-8 9 8v10H3z"/>@break
@case('book')<path d="M4 4h7a3 3 0 013 3v13a2 2 0 00-2-2H4zM20 4h-7"/><path d="M20 4v14h-6"/>@break
@case('flask')<path d="M9 3h6M10 3v6L4 20h16l-6-11V3"/>@break
@case('user')<circle cx="12" cy="8" r="4"/><path d="M4 21c1-5 15-5 16 0"/>@break
@case('gear')<circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/>@break
@case('out')<path d="M9 4H4v16h5M16 8l4 4-4 4M20 12H9"/>@break
@endswitch
</svg>
