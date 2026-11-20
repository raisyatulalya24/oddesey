<?php
namespace App\Http\Controllers;
use App\Models\Material;
use Illuminate\Http\Request;

// "My Exploration": riwayat materi, eksperimen, bookmark
class DashboardController extends Controller
{
    public function index(Request $r)
    {
        $u = $r->user();
        $total = Material::where('is_published', true)->count();
        $done = $u->histories()->where('status', 'completed')->count();
        return view('dashboard', [
            'tab' => $r->query('tab', 'riwayat'),
            'pct' => $total ? round($done / $total * 100) : 0, 'done' => $done, 'total' => $total, 'badges' => $u->badgeCount(),
            'last' => $u->histories()->with('material.subject')->latest('last_viewed_at')->first(),
            'histories' => $u->histories()->with('material.subject')->latest('last_viewed_at')->get(),
            'experiments' => $u->experiments()->with('simulation.material', 'answers.question')->latest()->get(),
            'bookmarks' => $u->bookmarks()->with('subject')->get(),
        ]);
    }
}
