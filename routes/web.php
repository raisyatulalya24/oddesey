<?php
use App\Http\Controllers\{TrackController, AuthController, DashboardController, ExperimentController, HomeController, MaterialController, ProfileController, ProgressController, SimulationController};
use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'landing'])->name('home');
Route::view('/privacy', 'privacy')->name('privacy');


Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Exploration Hub & Simulation Lab dapat dilihat publik
Route::get('/materials', [MaterialController::class, 'index'])->name('materials.index');
Route::get('/materials/{material}', [MaterialController::class, 'show'])->name('materials.show');
Route::get('/simulations', [SimulationController::class, 'index'])->name('simulations.index');
Route::get('/simulations/{simulation}', [SimulationController::class, 'show'])->name('simulations.show');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/materials/{material}/bookmark', [MaterialController::class, 'bookmark'])->name('materials.bookmark');
    Route::post('/materials/{material}/complete', [MaterialController::class, 'complete'])->name('materials.complete');
    Route::get('/progress', [ProgressController::class, 'index'])->name('progress');
    Route::get('/certificate/{subject}', [ProgressController::class, 'certificate'])->name('certificate');
    Route::post('/track', [TrackController::class, 'store'])->middleware('throttle:30,1')->name('track');
    Route::post('/experiments', [ExperimentController::class, 'store'])->name('experiments.store');
    Route::delete('/experiments/{experiment}', [ExperimentController::class, 'destroy'])->name('experiments.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::prefix('{res}')->where(['res' => 'subjects|materials|simulations|parameters|questions'])->group(function () {
        Route::get('/', [AdminController::class, 'index'])->name('index');
        Route::get('/create', [AdminController::class, 'create'])->name('create');
        Route::post('/', [AdminController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'destroy'])->name('destroy');
    });
});
