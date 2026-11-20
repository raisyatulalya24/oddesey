<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Subject extends Model
{
    protected $guarded = [];
    protected $casts = ['is_active' => 'bool'];
    public function getRouteKeyName() { return 'slug'; }
    public function materials() { return $this->hasMany(Material::class); }
    public function scopeActive($q) { return $q->where('is_active', true); }
}
