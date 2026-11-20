<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SimulationParameter extends Model
{
    protected $guarded = [];
    public function simulation() { return $this->belongsTo(Simulation::class); }
}
