<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = ['name', 'email', 'password', 'role', 'provider', 'provider_id'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array { return ['password' => 'hashed']; }
    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function experiments() { return $this->hasMany(Experiment::class); }
    public function histories() { return $this->hasMany(LearningHistory::class); }
    public function bookmarks() { return $this->belongsToMany(Material::class, 'bookmarks')->withTimestamps(); }
    public function studySessions() { return $this->hasMany(StudySession::class); }
    /** Lencana = materi selesai + eksperimen tersimpan */
    public function badgeCount(): int { return $this->histories()->where('status', 'completed')->count() + $this->experiments()->count(); }
}
