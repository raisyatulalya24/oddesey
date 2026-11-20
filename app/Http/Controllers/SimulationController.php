<?php
namespace App\Http\Controllers;
use App\Models\Simulation;

class SimulationController extends Controller
{
    public function index() { return view('simulations.index', ['sims' => Simulation::with('material.subject')->where('is_active', true)->get()]); }

    public function show(Simulation $simulation)
    {
        abort_unless($simulation->is_active, 404);
        $simulation->load('parameters', 'material.subject', 'questions');
        return view('simulations.show', ['sim' => $simulation]);
    }
}
