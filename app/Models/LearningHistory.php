<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LearningHistory extends Model
{
    protected $guarded = [];
    protected $casts = ['last_viewed_at' => 'datetime'];
    public function user() { return $this->belongsTo(User::class); }
    public function material() { return $this->belongsTo(Material::class); }
    public function markCompleted(): void { $this->update(['status' => 'completed']); }
}
