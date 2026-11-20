@extends('layouts.app')
@section('title', $c['title'])
@section('content')
<h1>{{ $row ? 'Ubah' : 'Tambah' }} {{ $c['title'] }}</h1><br>@include('admin.nav')
<form class="card" style="max-width:680px" method="post" action="{{ $row ? route('admin.update', [$c['res'], $row->id]) : route('admin.store', $c['res']) }}">@csrf @if($row) @method('PUT') @endif
@foreach($c['f'] as $k => $t)
<div class="fld">
 @if($t === 'bool')<label class="row"><input type="checkbox" name="{{ $k }}" value="1" @checked(old($k, $row?->$k ?? true))> {{ $k }}</label>
 @else<label for="{{ $k }}">{{ $k }}</label>
  @if($t === 'textarea')<textarea id="{{ $k }}" name="{{ $k }}" rows="6">{{ old($k, $row?->$k) }}</textarea>
  @elseif(is_array($t) && array_is_list($t))<select id="{{ $k }}" name="{{ $k }}">@foreach($t[0]::orderBy($t[1])->get() as $o)<option value="{{ $o->id }}" @selected(old($k, $row?->$k) == $o->id)>{{ $o->{$t[1]} }}</option>@endforeach</select>
  @elseif(is_array($t))<select id="{{ $k }}" name="{{ $k }}">@foreach($t as $v => $l)<option value="{{ $v }}" @selected(old($k, $row?->$k) == $v)>{{ $l }}</option>@endforeach</select>
  @else<input id="{{ $k }}" name="{{ $k }}" type="{{ str_starts_with($t, 'number') ? 'number' : 'text' }}" @if($t === 'number') step="any" @endif value="{{ old($k, $row?->$k) }}">@endif
 @endif
 @error($k)<div class="err">{{ $message }}</div>@enderror
</div>
@endforeach
<div class="row"><button class="btn">Simpan</button><a class="btn ghost" href="{{ route('admin.index', $c['res']) }}">Batal</a></div></form>
@endsection
