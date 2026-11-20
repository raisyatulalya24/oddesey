@extends('layouts.app')
@section('title', $c['title'])
@section('content')
<div class="row between"><h1>{{ $c['title'] }}</h1><a class="btn sm" href="{{ route('admin.create', $c['res']) }}">+ Tambah</a></div><br>@include('admin.nav')
<div class="card" style="overflow-x:auto"><table><thead><tr>@foreach($c['cols'] as $k)<th>{{ $k }}</th>@endforeach<th></th></tr></thead><tbody>
@forelse($rows as $r)<tr>@foreach($c['cols'] as $k)<td>@php $v = $r->$k; @endphp {{ is_bool($v) ? ($v ? 'Ya' : 'Tidak') : \Illuminate\Support\Str::limit((string) $v, 60) }}</td>@endforeach
<td class="row"><a class="btn ghost sm" href="{{ route('admin.edit', [$c['res'], $r->id]) }}">Ubah</a><form method="post" action="{{ route('admin.destroy', [$c['res'], $r->id]) }}" onsubmit="return confirm('Hapus data ini?')">@csrf @method('DELETE')<button class="btn danger sm">Hapus</button></form></td></tr>
@empty<tr><td colspan="9">Belum ada data.</td></tr>@endforelse</tbody></table></div>
<div style="margin-top:1rem">{{ $rows->links() }}</div>
@endsection
