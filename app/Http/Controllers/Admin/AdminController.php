<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Experiment, Material, ReflectionQuestion, Simulation, SimulationParameter, Subject, User};
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// CRUD generik untuk modul: Bidang, Materi, Simulasi & Parameter, Pertanyaan Refleksi
class AdminController extends Controller
{
    private function cfg(string $res): array
    {
        $types = ['projectile' => 'Gerak Parabola', 'reaction' => 'Laju Reaksi', 'orbit' => 'Orbit Planet', 'photosynthesis' => 'Fotosintesis', 'wave' => 'Gelombang'];
        $all = [
            'subjects' => ['m' => Subject::class, 'title' => 'Bidang Ilmu', 'slug' => 'name', 'cols' => ['name', 'icon', 'is_active'],
                'f' => ['name' => 'text', 'description' => 'textarea', 'icon' => ['leaf' => 'Daun', 'atom' => 'Atom', 'flask' => 'Labu', 'planet' => 'Planet', 'calc' => 'Kalkulator'], 'is_active' => 'bool']],
            'materials' => ['m' => Material::class, 'title' => 'Materi', 'slug' => 'title', 'cols' => ['title', 'level', 'is_published'],
                'f' => ['subject_id' => [Subject::class, 'name'], 'title' => 'text', 'summary' => 'text', 'content' => 'textarea', 'level' => ['dasar' => 'Dasar', 'menengah' => 'Menengah', 'lanjut' => 'Lanjut'], 'is_published' => 'bool']],
            'simulations' => ['m' => Simulation::class, 'title' => 'Simulasi', 'cols' => ['title', 'type', 'is_active'],
                'f' => ['material_id' => [Material::class, 'title'], 'title' => 'text', 'type' => $types, 'description' => 'textarea', 'is_active' => 'bool']],
            'parameters' => ['m' => SimulationParameter::class, 'title' => 'Parameter Simulasi', 'cols' => ['label', 'name', 'min_value', 'max_value', 'default_value'],
                'f' => ['simulation_id' => [Simulation::class, 'title'], 'name' => 'text', 'label' => 'text', 'unit' => 'text?', 'min_value' => 'number', 'max_value' => 'number', 'default_value' => 'number', 'step' => 'number']],
            'questions' => ['m' => ReflectionQuestion::class, 'title' => 'Pertanyaan Refleksi', 'cols' => ['question', 'sort_order', 'is_active'],
                'f' => ['simulation_id' => [Simulation::class, 'title'], 'question' => 'textarea', 'sort_order' => 'number', 'is_active' => 'bool']],
        ];
        abort_unless(isset($all[$res]), 404);
        return $all[$res] + ['res' => $res];
    }

    private function rules(array $c): array
    {
        $r = [];
        foreach ($c['f'] as $k => $t) {
            $r[$k] = match (true) {
                $t === 'bool' => 'boolean', $t === 'number' => 'required|numeric', $t === 'text?' => 'nullable|string|max:50',
                $t === 'textarea' => 'nullable|string', is_array($t) && array_is_list($t) => 'required|exists:' . (new $t[0])->getTable() . ',id',
                is_array($t) => 'required|in:' . implode(',', array_keys($t)), default => 'required|string|max:255',
            };
        }
        return $r;
    }

    public function dashboard()
    {
        return view('admin.dashboard', ['stats' => ['Siswa' => User::where('role', 'student')->count(), 'Materi' => Material::count(), 'Simulasi' => Simulation::count(), 'Eksperimen' => Experiment::count()]]);
    }

    public function index(string $res) { $c = $this->cfg($res); return view('admin.index', ['c' => $c, 'rows' => $c['m']::latest('id')->paginate(15)]); }
    public function create(string $res) { return view('admin.form', ['c' => $this->cfg($res), 'row' => null]); }
    public function edit(string $res, int $id) { $c = $this->cfg($res); return view('admin.form', ['c' => $c, 'row' => $c['m']::findOrFail($id)]); }

    public function store(Request $r, string $res)
    {
        $c = $this->cfg($res);
        $d = $this->data($r, $c);
        $c['m']::create($d);
        return redirect()->route('admin.index', $res)->with('ok', $c['title'] . ' berhasil ditambahkan.');
    }

    public function update(Request $r, string $res, int $id)
    {
        $c = $this->cfg($res);
        $row = $c['m']::findOrFail($id);
        $row->update($this->data($r, $c, $row));
        return redirect()->route('admin.index', $res)->with('ok', $c['title'] . ' berhasil diperbarui.');
    }

    public function destroy(string $res, int $id)
    {
        $c = $this->cfg($res);
        $c['m']::findOrFail($id)->delete();
        return back()->with('ok', $c['title'] . ' dihapus.');
    }

    private function data(Request $r, array $c, $row = null): array
    {
        foreach ($c['f'] as $k => $t) if ($t === 'bool') $r->merge([$k => $r->boolean($k)]);
        $d = $r->validate($this->rules($c));
        if (isset($c['slug'])) {
            $base = Str::slug($d[$c['slug']]); $slug = $base; $i = 2;
            while ($c['m']::where('slug', $slug)->when($row, fn ($q) => $q->where('id', '!=', $row->id))->exists()) $slug = $base . '-' . $i++;
            $d['slug'] = $slug;
        }
        return $d;
    }
}
