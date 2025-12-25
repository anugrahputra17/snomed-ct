<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('diagnosis_component_values', function (Blueprint $table) {
            $table->id();

            $table->foreignId('diagnosis_component_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('value_text')->nullable();
            $table->string('snomed_concept_id')->nullable();
            $table->string('snomed_fsn')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnosis_component_values');
    }
};
