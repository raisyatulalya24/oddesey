# Oddesey — Laravel 12 / PHP 8.2 / MySQL

1. `composer create-project laravel/laravel:^12.0 oddesey-app` lalu salin isi folder ini ke dalamnya (`xcopy ... /E /Y /I`).
2. Salin isi `env-mysql.txt` ke `.env`, buat database `oddesey` (phpMyAdmin), lalu `php artisan migrate:fresh --seed`.
3. Jalankan `download-assets.bat` (mengunduh gambar desain Figma ke `public/img`).
4. (Opsional, login Google) `composer require laravel/socialite`, isi kunci di `.env`.
5. `php artisan serve`

Akun demo (password `password`): admin@oddesey.test (admin), siswa@oddesey.test (siswa). Siapa pun juga bisa mendaftar di /register dengan email apa saja.

Waktu belajar dicatat nyata: `public/js/track.js` menghitung detik aktif (tab terlihat + ada aktivitas) dan mengirimnya ke `TrackController` → tabel `study_sessions`.
