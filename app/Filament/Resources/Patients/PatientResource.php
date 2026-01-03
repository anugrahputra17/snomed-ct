<?php

namespace App\Filament\Resources\Patients;

use App\Filament\Resources\Patients\Pages\CreatePatient;
use App\Filament\Resources\Patients\Pages\EditPatient;
use App\Filament\Resources\Patients\Pages\ListPatients;
use App\Filament\Resources\Patients\Schemas\PatientForm;
use App\Filament\Resources\Patients\Tables\PatientsTable;
use App\Filament\Resources\Patients\RelationManagers\DiagnosesRelationManager;
use App\Models\Patient;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PatientResource extends Resource
{
    /**
     * Model yang digunakan resource ini
     */
    protected static ?string $model = Patient::class;
    
    protected static ?string $modelLabel = 'Pasien';
    protected static ?string $pluralModelLabel = 'Pasien';

    /**
     * Icon navigasi sidebar
     */
    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedRectangleStack;

    /**
     * Judul record (dipakai di breadcrumb & header)
     */
    protected static ?string $recordTitleAttribute = 'name';

    /**
     * FORM
     * Digunakan untuk Create & Edit
     */
    public static function form(Schema $schema): Schema
    {
        return PatientForm::configure($schema);
    }

    /**
     * TABLE
     * Digunakan untuk halaman List
     */
    public static function table(Table $table): Table
    {
        return PatientsTable::configure($table);
    }

    /**
     * RELATIONS
     * Tambahkan RelationManager di sini jika perlu
     */
    public static function getRelations(): array
    {
        return [
            DiagnosesRelationManager::class,
        ];
    }

    /**
     * PAGES
     */
    public static function getPages(): array
    {
        return [
            'index'  => ListPatients::route('/'),
            'create' => CreatePatient::route('/create'),
            'edit'   => EditPatient::route('/{record}/edit'),
        ];
    }
}
