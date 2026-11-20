<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

// Login Google. Aktif hanya jika kunci ada di .env dan paket socialite terpasang.
class SocialAuthController extends Controller
{
    public static function enabled(string $p): bool
    {
        $c = config("oddesey.$p");
        return class_exists(Socialite::class) && $c && filled($c['client_id']) && filled($c['client_secret']);
    }

    private function setup(string $p): void
    {
        abort_unless(self::enabled($p), 404);
        $c = config("oddesey.$p");
        config(["services.$p" => ['client_id' => $c['client_id'], 'client_secret' => $c['client_secret'], 'redirect' => url("/auth/$p/callback")]]);
    }

    public function redirect(string $provider)
    {
        $this->setup($provider);
        return Socialite::driver($provider)->redirect();
    }

    public function callback(Request $r, string $provider)
    {
        $this->setup($provider);
        try { $s = Socialite::driver($provider)->user(); }
        catch (\Throwable $e) { return redirect()->route('login')->withErrors(['email' => 'Login dengan ' . ucfirst($provider) . ' gagal. Coba lagi.']); }
        if (!$s->getEmail()) return redirect()->route('login')->withErrors(['email' => 'Akun tidak memberikan alamat email.']);

        $u = User::firstOrCreate(['email' => $s->getEmail()], ['name' => $s->getName() ?: Str::before($s->getEmail(), '@'), 'password' => Str::random(40), 'role' => 'student']);
        if (!$u->provider) $u->update(['provider' => $provider, 'provider_id' => $s->getId()]);
        Auth::login($u, true);
        $r->session()->regenerate();
        return redirect()->intended($u->isAdmin() ? '/admin' : '/dashboard');
    }
}
