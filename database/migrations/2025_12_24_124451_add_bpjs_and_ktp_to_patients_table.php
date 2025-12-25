<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            $table->string('no_bpjs', 20)
                ->nullable()
                ->after('medical_record_number');

            $table->string('no_ktp', 16)
                ->nullable()
                ->after('no_bpjs');

        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn(['no_bpjs', 'no_ktp']);
        });
    }
};
