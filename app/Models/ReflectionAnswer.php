<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ReflectionAnswer extends Model
{
    protected $guarded = [];
    public function experiment() { return $this->belongsTo(Experiment::class); }
    public function question() { return $this->belongsTo(ReflectionQuestion::class, 'reflection_question_id'); }
}
