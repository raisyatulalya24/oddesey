<?php
namespace App\Http\Controllers;
use App\Models\{Material, Subject};
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $r)
    {
        $u = $r->user();
        $total = Material::where('is_published', true)->count();
        $series = [];
        for ($i = 5; $i >= 0; $i--) {
            $end = now()->startOfMonth()->subMonths($i)->endOfMonth();
            $c = $u->histories()->where('status', 'completed')->where('updated_at', '<=', $end)->count();
            $series[] = ['m' => $end->format('n'), 'p' => $total ? (int) round($c / $total * 100) : 0];
        }

        // Waktu belajar NYATA (detik aktif dari study_sessions)
        $sec = fn ($from = null, $to = null) => (int) $u->studySessions()
            ->when($from, fn ($q) => $q->where('study_date', '>=', $from->toDateString()))
            ->when($to, fn ($q) => $q->where('study_date', '<=', $to->toDateString()))->sum('seconds');
        $curStart = now()->startOfMonth(); $prevStart = now()->startOfMonth()->subMonth();
        $cur = $sec($curStart); $prev = $sec($prevStart, $prevStart->copy()->endOfMonth());
        $delta = $prev > 0 ? (int) round(($cur - $prev) / $prev * 100) : null;
        $allSec = $sec();

        $n = $u->badgeCount();
        $defs = [['Penjelajah', 1, '#f3b440', '#5a2fa8', '#ffd45a', '#8b4bd6'], ['Peneliti', 5, '#6a3fc2', '#7d86d8', '#dfe3ff', '#4a2c94'], ['Ilmuwan', 10, '#c98a73', '#b0705a', '#e8b3a0', '#8c4a38'], ['Master', 20, '#9a6a2e', '#7c4f1d', '#e0b060', '#5b3510']];
        $medals = array_map(fn ($d) => ['name' => $d[0], 'need' => $d[1], 'on' => $n >= $d[1], 'c1' => $d[2], 'c2' => $d[3], 'c3' => $d[4], 'rib' => $d[5]], $defs);

        $doneBy = $u->histories()->where('status', 'completed')->with('material:id,subject_id')->get()->groupBy(fn ($h) => $h->material->subject_id)->map->count();
        $certSubject = Subject::active()->withCount(['materials' => fn ($q) => $q->where('is_published', true)])->get()
            ->first(fn ($s) => $s->materials_count > 0 && ($doneBy[$s->id] ?? 0) >= $s->materials_count);

        return view('progress', compact('series', 'delta', 'allSec', 'cur', 'medals', 'certSubject'));
    }

    public function certificate(Request $r, Subject $subject)
    {
        $u = $r->user();
        $total = $subject->materials()->where('is_published', true)->count();
        $done = $u->histories()->where('status', 'completed')->whereHas('material', fn ($q) => $q->where('subject_id', $subject->id))->count();
        abort_unless($total > 0 && $done >= $total, 403, 'Selesaikan semua materi bidang ini terlebih dahulu.');
        return view('certificate', compact('subject'));
    }
}
