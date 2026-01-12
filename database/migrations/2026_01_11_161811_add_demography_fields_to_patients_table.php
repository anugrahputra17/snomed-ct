<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('patients', function (Blueprint $table) {

            $table->string('birth_place')
                ->nullable()
                ->after('name');

            $table->unsignedSmallInteger('age')
                ->nullable()
                ->after('birth_date');

            $table->string('religion', 50)
                ->nullable()
                ->after('age');

            $table->string('ethnicity', 50)
                ->nullable()
                ->after('religion');

            $table->string('citizenship', 50)
                ->default('WNI')
                ->after('ethnicity');
        });
    }

    public function down(): void
    {
        Schema::table('patients', function (Blueprint $table) {
            $table->dropColumn([
                'birth_place',
                'age',
                'religion',
                'ethnicity',
                'citizenship',
            ]);
        });
    }
};
