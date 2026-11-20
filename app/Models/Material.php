<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Material extends Model
{
    protected $guarded = [];
    protected $casts = ['is_published' => 'bool'];
    public function getRouteKeyName() { return 'slug'; }
    public function subject() { return $this->belongsTo(Subject::class); }
    public function simulations() { return $this->hasMany(Simulation::class); }
    public function simulation() { return $this->hasOne(Simulation::class)->where('is_active', true); }
    public function bookmarks() { return $this->hasMany(Bookmark::class); }
    public function histories() { return $this->hasMany(LearningHistory::class); }
}
