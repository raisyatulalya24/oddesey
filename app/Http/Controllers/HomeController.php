<?php
namespace App\Http\Controllers;
use App\Models\Subject;

class HomeController extends Controller
{
    public function landing() { return view('landing', ['subjects' => Subject::active()->get()]); }
}
