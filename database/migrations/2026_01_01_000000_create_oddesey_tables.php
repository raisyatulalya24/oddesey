<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->string('role')->default('student'));
        Schema::create('subjects', function (Blueprint $t) {
            $t->id(); $t->string('name'); $t->string('slug')->unique(); $t->text('description')->nullable();
            $t->string('icon')->nullable(); $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('materials', function (Blueprint $t) {
            $t->id(); $t->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $t->string('title'); $t->string('slug')->unique(); $t->string('summary')->nullable();
            $t->longText('content'); $t->string('level')->default('dasar'); $t->boolean('is_published')->default(true); $t->timestamps();
        });
        Schema::create('simulations', function (Blueprint $t) {
            $t->id(); $t->foreignId('material_id')->constrained()->cascadeOnDelete();
            $t->string('title'); $t->string('type'); $t->text('description')->nullable(); $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('simulation_parameters', function (Blueprint $t) {
            $t->id(); $t->foreignId('simulation_id')->constrained()->cascadeOnDelete();
            $t->string('name'); $t->string('label'); $t->string('unit')->nullable();
            $t->decimal('min_value', 10, 2); $t->decimal('max_value', 10, 2); $t->decimal('default_value', 10, 2); $t->decimal('step', 10, 2)->default(1); $t->timestamps();
        });
        Schema::create('reflection_questions', function (Blueprint $t) {
            $t->id(); $t->foreignId('simulation_id')->constrained()->cascadeOnDelete();
            $t->text('question'); $t->unsignedInteger('sort_order')->default(0); $t->boolean('is_active')->default(true); $t->timestamps();
        });
        Schema::create('experiments', function (Blueprint $t) {
            $t->id(); $t->foreignId('user_id')->constrained()->cascadeOnDelete(); $t->foreignId('simulation_id')->constrained()->cascadeOnDelete();
            $t->json('parameters_used'); $t->json('result_data'); $t->timestamps();
        });
        Schema::create('reflection_answers', function (Blueprint $t) {
            $t->id(); $t->foreignId('experiment_id')->constrained()->cascadeOnDelete();
            $t->foreignId('reflection_question_id')->constrained()->cascadeOnDelete(); $t->text('answer'); $t->timestamps();
        });
        Schema::create('bookmarks', function (Blueprint $t) {
            $t->id(); $t->foreignId('user_id')->constrained()->cascadeOnDelete(); $t->foreignId('material_id')->constrained()->cascadeOnDelete();
            $t->timestamps(); $t->unique(['user_id', 'material_id']);
        });
        Schema::create('learning_histories', function (Blueprint $t) {
            $t->id(); $t->foreignId('user_id')->constrained()->cascadeOnDelete(); $t->foreignId('material_id')->constrained()->cascadeOnDelete();
            $t->string('status')->default('viewed'); $t->timestamp('last_viewed_at')->nullable(); $t->timestamps(); $t->unique(['user_id', 'material_id']);
        });
    }
    public function down(): void
    {
        foreach (['learning_histories','bookmarks','reflection_answers','experiments','reflection_questions','simulation_parameters','simulations','materials','subjects'] as $t) Schema::dropIfExists($t);
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('role'));
    }
};
