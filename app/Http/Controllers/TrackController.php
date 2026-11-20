<?php
namespace App\Http\Controllers;
use App\Models\StudySession;
use Illuminate\Http\Request;

// Menerima "detak" waktu aktif dari browser (maks 60 detik per request)
class TrackController extends Controller
{
    public function store(Request $r)
    {
        $d = $r->validate(['material_id' => 'required|exists:materials,id', 'seconds' => 'required|integer|min:1|max:60']);
        $s = StudySession::firstOrCreate(['user_id' => $r->user()->id, 'material_id' => $d['material_id'], 'study_date' => today()->toDateString()], ['seconds' => 0]);
        $s->increment('seconds', $d['seconds']);
        return response()->noContent();
    }
}
