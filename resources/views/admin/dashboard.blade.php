@extends('layouts.app')
@section('title', 'Admin')
@section('content')
<h1>Dashboard Admin</h1><p class="lead">Kelola konten Oddesey.</p>@include('admin.nav')
<div class="grid g3">@foreach($stats as $l => $n)<div class="card stat"><b>{{ $n }}</b><div class="small">{{ $l }}</div></div>@endforeach</div>
@endsection
