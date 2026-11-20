@auth
<script>window.TRACK = @json(['url' => route('track'), 'token' => csrf_token(), 'material' => $material_id]);</script>
<script src="{{ asset('js/track.js') }}"></script>
@endauth
