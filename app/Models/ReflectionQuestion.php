<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ReflectionQuestion extends Model
{
    protected $guarded = [];
    protected $casts = ['is_active' => 'bool'];
    public function simulation() { return $this->belongsTo(Simulation::class); }
    public function answers() { return $this->hasMany(ReflectionAnswer::class); }
}
