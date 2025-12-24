<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use App\Services\SnomedService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\HasDropdown;
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

class DiagnosesRelationManager extends RelationManager
{
    protected static string $relationship = 'diagnoses';

    public function form(Schema $schema): Schema
    {
        return $schema->components([


            TextInput::make('Items')
                ->label('Keluhan Utama')
                ->required(),

            Select::make('snomed_code')
            ->label('Cari Diagnosa (SNOMED CT)')
            ->searchable()
            ->getSearchResultsUsing(
            fn (string $search): array =>
            app(\App\Services\SnomedService::class)->searchByTerm($search)
    )
            ->getOptionLabelUsing(fn ($value) =>
            $value
                ? app(\App\Services\SnomedService::class)->formatLabel($value)
                : null
    )
            ->required(),

            TextInput::make('snomed_term')
                ->label('SNOMED Term')
                ->disabled()
                ->dehydrated(),
                

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
                    ->limit(50),

                TextColumn::make('snomed_code')
                    ->label('SNOMED Code'),

                TextColumn::make('snomed_term')
                    ->label('SNOMED Term'),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->dateTime('d M Y H:i'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        return $this->attachSnomed($data);
                    }),
            ])
            ->recordActions([
                EditAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        return $this->attachSnomed($data);
                    }),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    protected function attachSnomed(array $data): array
    {
        if (! empty($data['snomed_code'])) {
            $term = app(SnomedService::class)
                ->getByConceptId($data['snomed_code']);

            $data['snomed_term'] = $term;

            if (empty($data['diagnosis_text'])) {
                $data['diagnosis_text'] = $term;
            }
        }

        return $data;
    }
}
