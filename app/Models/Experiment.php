<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Experiment extends Model
{
    protected $guarded = [];
    protected $casts = ['parameters_used' => 'array', 'result_data' => 'array'];
    public function user() { return $this->belongsTo(User::class); }
    public function simulation() { return $this->belongsTo(Simulation::class); }
    public function answers() { return $this->hasMany(ReflectionAnswer::class); }
    public function getSummary(): array { return ['parameter' => $this->parameters_used, 'hasil' => $this->result_data]; }
}
