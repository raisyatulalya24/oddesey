<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Waktu belajar nyata: detik aktif per user, per materi, per hari
return new class extends Migration {
    public function up(): void
    {
        Schema::create('study_sessions', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('material_id')->constrained()->cascadeOnDelete();
            $t->date('study_date');
            $t->unsignedInteger('seconds')->default(0);
            $t->timestamps();
            $t->unique(['user_id', 'material_id', 'study_date']);
        });
    }
    public function down(): void { Schema::dropIfExists('study_sessions'); }
};
