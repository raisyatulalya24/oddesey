<?php
namespace App\Http\Controllers;
use App\Models\{Experiment, LearningHistory, ReflectionAnswer, Simulation};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExperimentController extends Controller
{
    public function store(Request $r)
    {
        $d = $r->validate([
            'simulation_id' => 'required|exists:simulations,id', 'parameters_used' => 'required|json', 'result_data' => 'required|json',
            'answers' => 'array', 'answers.*' => 'nullable|string|max:2000',
        ]);
        $sim = Simulation::with('questions')->findOrFail($d['simulation_id']);
        $valid = $sim->questions->pluck('id')->all();

        DB::transaction(function () use ($d, $r, $sim, $valid) {
            $e = Experiment::create(['user_id' => $r->user()->id, 'simulation_id' => $sim->id,
                'parameters_used' => json_decode($d['parameters_used'], true), 'result_data' => json_decode($d['result_data'], true)]);
            $rows = [];
            foreach ($d['answers'] ?? [] as $qid => $a) {
                if (filled($a) && in_array((int) $qid, $valid)) $rows[] = ['experiment_id' => $e->id, 'reflection_question_id' => $qid, 'answer' => $a, 'created_at' => now(), 'updated_at' => now()];
            }
            ReflectionAnswer::insert($rows);
            LearningHistory::updateOrCreate(['user_id' => $r->user()->id, 'material_id' => $sim->material_id], ['last_viewed_at' => now()])->markCompleted();
        });
        return redirect()->route('dashboard', ['tab' => 'eksperimen'])->with('ok', 'Eksperimen berhasil disimpan! ✨');
    }

    public function destroy(Request $r, Experiment $experiment)
    {
        abort_unless($experiment->user_id === $r->user()->id, 403);
        $experiment->delete();
        return back()->with('ok', 'Eksperimen dihapus.');
    }
}
