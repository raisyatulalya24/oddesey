<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8"><meta name="csrf-token" content="{{ csrf_token() }}"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title', 'Oddesey') · Explore. Learn. Discover.</title>
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto+Mono:wght@400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/oddesey.css') }}">
</head>
<body>
@include('partials.defs')
@yield('body')
@stack('scripts')
</body>
</html>
