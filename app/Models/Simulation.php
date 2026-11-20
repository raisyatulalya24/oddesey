<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Simulation extends Model
{
    protected $guarded = [];
    protected $casts = ['is_active' => 'bool'];
    public function material() { return $this->belongsTo(Material::class); }
    public function parameters() { return $this->hasMany(SimulationParameter::class); }
    public function questions() { return $this->hasMany(ReflectionQuestion::class)->where('is_active', true)->orderBy('sort_order'); }
    public function experiments() { return $this->hasMany(Experiment::class); }
}
