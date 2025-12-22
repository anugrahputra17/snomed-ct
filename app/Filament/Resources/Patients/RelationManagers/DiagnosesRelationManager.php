<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use App\Services\SnomedService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Form;

class DiagnosesRelationManager extends RelationManager
{
    protected static string $relationship = 'diagnoses';

    public function form(Schema $schema): Schema
{
    return $schema->components([
        Select::make('snomed_code')
            ->label('Cari Diagnosa (SNOMED CT)')
            ->searchable()
            ->getSearchResultsUsing(
                fn (string $search): array =>
                    app(SnomedService::class)->search($search)
            )
            ->required(),

        TextInput::make('snomed_term')
            ->label('SNOMED Term')
            ->disabled(),

        Textarea::make('diagnosis_text')
            ->label('Diagnosa Dokter')
            ->required(),
    ]);
}


    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('diagnosis_text')
                    ->label('Diagnosa')
                    ->limit(50)
                    ->searchable(),

                TextColumn::make('snomed_code')
                    ->label('SNOMED Code'),

                TextColumn::make('snomed_term')
                    ->label('SNOMED Term'),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
    protected function mutateFormDataBeforeCreate(array $data): array
{
    return $this->attachSnomedTerm($data);
}

protected function mutateFormDataBeforeSave(array $data): array
{
    return $this->attachSnomedTerm($data);
}

protected function attachSnomedTerm(array $data): array
{
    if (! empty($data['snomed_code'])) {
        $results = app(SnomedService::class)->search($data['snomed_code']);

        $data['snomed_term'] =
            $results[$data['snomed_code']] ?? $data['snomed_term'] ?? null;

        $data['diagnosis_text'] ??= $data['snomed_term'];
    }

    return $data;
}

}
