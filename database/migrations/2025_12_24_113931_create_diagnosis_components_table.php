<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('diagnosis_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diagnosis_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('component'); // Problem / Person
            $table->string('variable');  // Keluhan Utama

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnosis_components');
    }
};
