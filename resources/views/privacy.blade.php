@extends('layouts.base')
@section('title', 'Kebijakan Privasi')
@section('body')
<main style="max-width:760px;margin:0 auto;padding:3rem 1.5rem 5rem">
<a href="{{ route('home') }}" class="logo" style="color:var(--navy);font-size:20px"><b>O</b>ddesey</a>
<h1 style="margin:1.5rem 0 .5rem">Kebijakan Privasi</h1><p class="small">Terakhir diperbarui: {{ now()->translatedFormat('d F Y') }}</p>
<div class="prose" style="margin-top:1.5rem"><p>Oddesey adalah platform belajar interaktif (materi dan simulasi laboratorium digital). Halaman ini menjelaskan data apa yang kami simpan dan bagaimana kami menggunakannya.</p>
<h2 style="margin:1.5rem 0 .5rem;font-size:20px">Data yang kami kumpulkan</h2>
<p>Saat mendaftar, kami menyimpan nama dan alamat email.</p>
<p>Saat belajar, kami menyimpan riwayat materi yang dibuka, bookmark, hasil dan jawaban refleksi eksperimen, serta lama waktu aktifmu di halaman materi dan simulasi.</p>
<h2 style="margin:1.5rem 0 .5rem;font-size:20px">Cara kami menggunakan data</h2>
<p>Data hanya dipakai untuk menampilkan progres, riwayat, dan pencapaianmu di dalam Oddesey. Kami tidak menjual atau membagikan datamu kepada pihak ketiga untuk iklan.</p>
<h2 style="margin:1.5rem 0 .5rem;font-size:20px">Penyimpanan dan penghapusan</h2>
<p>Password disimpan dalam bentuk terenkripsi (hash). Kamu dapat meminta penghapusan akun dan seluruh datamu dengan menghubungi kami di <a href="mailto:{{ config('oddesey.contact_email') }}" style="color:var(--pur);font-weight:600">{{ config('oddesey.contact_email') }}</a>.</p>
<h2 style="margin:1.5rem 0 .5rem;font-size:20px">Kontak</h2>
<p>Pertanyaan tentang kebijakan ini dapat dikirim ke {{ config('oddesey.contact_email') }}.</p></div>
</main>
@endsection
