<?php
namespace App\Http\Controllers;
use App\Models\{Simulation, Subject};
use Illuminate\Http\Request;

class SimulationController extends Controller
{
    public function index(Request $r)
    {
        $sims = Simulation::with('material.subject')->where('is_active', true)
            ->when($r->subject, fn ($q, $s) => $q->whereHas('material.subject', fn ($x) => $x->where('slug', $s)))
            ->when($r->q, fn ($q, $s) => $q->where('title', 'like', "%$s%"))
            ->orderBy('title')->get();
        return view('simulations.index', ['sims' => $sims, 'subjects' => Subject::active()->get()]);
    }

    public function show(Simulation $simulation)
    {
        abort_unless($simulation->is_active, 404);
        $simulation->load('parameters', 'material.subject', 'questions');
        return view('simulations.show', ['sim' => $simulation]);
    }
}
