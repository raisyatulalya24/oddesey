<?php
namespace App\Http\Controllers;
use App\Models\{LearningHistory, Material, Simulation, Subject};
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function index(Request $r)
    {
        $u = $r->user();
        $subjects = Subject::active()->withCount(['materials' => fn ($q) => $q->where('is_published', true)])->get();
        $doneBy = $u ? $u->histories()->where('status', 'completed')->with('material:id,subject_id')->get()->groupBy(fn ($h) => $h->material->subject_id)->map->count() : collect();
        $timeBy = $u ? $u->studySessions()->join('materials', 'materials.id', '=', 'study_sessions.material_id')->selectRaw('materials.subject_id as sid, SUM(study_sessions.seconds) as total')->groupBy('materials.subject_id')->pluck('total', 'sid') : collect();
        $simBy = Simulation::where('simulations.is_active', true)->join('materials', 'materials.id', '=', 'simulations.material_id')
            ->groupBy('materials.subject_id')->selectRaw('materials.subject_id as sid, COUNT(*) as n')->pluck('n', 'sid');
        $materials = null;
        if ($r->filled('q') || $r->filled('subject')) {
            $materials = Material::with('subject', 'simulation')->withCount(['simulations' => fn ($q) => $q->where('is_active', true)])->where('is_published', true)
                ->when($r->subject, fn ($q, $s) => $q->whereHas('subject', fn ($x) => $x->where('slug', $s)))
                ->when($r->q, fn ($q, $s) => $q->where(fn ($x) => $x->where('title', 'like', "%$s%")->orWhere('summary', 'like', "%$s%")))
                ->orderBy('title')->paginate(9)->withQueryString();
        }
        $marked = $u ? $u->bookmarks()->pluck('materials.id')->all() : [];
        return view('materials.index', compact('subjects', 'doneBy', 'timeBy', 'simBy', 'materials', 'marked'));
    }

    public function show(Request $r, Material $material)
    {
        abort_unless($material->is_published, 404);
        $u = $r->user();
        if ($u) LearningHistory::updateOrCreate(['user_id' => $u->id, 'material_id' => $material->id], ['last_viewed_at' => now()]);
        $material->load('subject', 'simulation', 'simulations');
        $marked = $u ? $u->bookmarks()->where('materials.id', $material->id)->exists() : false;
        $done = $u ? $u->histories()->where('material_id', $material->id)->where('status', 'completed')->exists() : false;
        return view('materials.show', compact('material', 'marked', 'done'));
    }

    public function bookmark(Request $r, Material $material)
    {
        $r->user()->bookmarks()->toggle($material->id);
        return back();
    }

    // Materi tanpa simulasi ditandai selesai manual
    public function complete(Request $r, Material $material)
    {
        LearningHistory::updateOrCreate(['user_id' => $r->user()->id, 'material_id' => $material->id], ['last_viewed_at' => now()])->markCompleted();
        return back()->with('ok', 'Materi ditandai selesai. 🎉');
    }
}
