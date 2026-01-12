<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('diagnoses', function (Blueprint $table) {
            $table->text('diagnosis_text')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('diagnoses', function (Blueprint $table) {
            $table->text('diagnosis_text')->nullable(false)->change();
        });
    }
};
