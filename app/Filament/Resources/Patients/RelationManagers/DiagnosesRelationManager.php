<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use App\Services\SnomedService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

class DiagnosesRelationManager extends RelationManager
{
    protected static string $relationship = 'diagnoses';

    /* =========================
     * FORM
     * ========================= */
    public function form(Schema $schema): Schema
    {
        return $schema->components([
        $this->problemRepeater()
            ->columnSpanFull(),
    ]);
    }

    protected function problemRepeater(): Repeater
{
    return Repeater::make('components')
        ->relationship()
        ->label('Komponen Diagnosis')
        ->columnSpanFull()
        ->defaultItems(1)
        ->collapsible()
        ->cloneable()
        ->itemLabel(fn ($state) => $state['variable'] ?? 'Variable Problem')
        ->addActionLabel('➕ Tambah Variable Problem')
        ->extraAttributes([
            'class' => 'bg-gray-50 dark:bg-gray-900 rounded-xl p-4 border border-gray-300 dark:border-gray-700',
        ])
        ->schema([
            Hidden::make('component')->default('Problem')->dehydrated(),

            Select::make('variable')
                ->label('Kategori Problem')
                ->required()
                ->options([
                    'Keluhan Utama'       => 'Keluhan Utama',
                    'Riwayat Penyakit'    => 'Riwayat Penyakit',
                    'Tindakan dan Hasil'  => 'Tindakan dan Hasil',
                    'Diagnosa'           => 'Diagnosa',
                    'Obat'               => 'Obat',
                ])
                ->searchable()
                ->columnSpanFull(),

            $this->snomedRepeater(),
        ]);
}


    protected function snomedRepeater(): Repeater
{
    return Repeater::make('values')
        ->relationship()
        ->label('Daftar Diagnosis (SNOMED CT)')
        ->defaultItems(1)
        ->columns(3)
        ->addActionLabel('➕ Tambah Diagnosis')
        ->collapsible()
        ->extraAttributes([
            'class' => 'bg-white dark:bg-gray-800 rounded-lg p-4 border border-primary-300 dark:border-primary-700',
        ])
        ->schema([
            Select::make('snomed_concept_id')
                ->label('Cari SNOMED CT')
                ->required()
                ->searchable()
                ->reactive()
                ->getSearchResultsUsing(
                    fn (string $search): array =>
                        app(SnomedService::class)->searchByTerm($search)
                )
                ->getOptionLabelUsing(
                    fn ($value) =>
                        $value ? app(SnomedService::class)->formatLabel($value) : null
                )
                ->afterStateUpdated(function ($state, callable $set) {
                    $set('snomed_fsn', $state
                        ? app(SnomedService::class)->formatLabel($state)
                        : null
                    );
                })
                ->columnSpan(1),

            TextInput::make('snomed_fsn')
                ->label('Snomed Term')
                ->disabled()
                ->dehydrated()
                ->extraAttributes([
                    'class' => 'bg-primary-50 dark:bg-primary-900 font-semibold',
                ])
                ->columnSpan(2),

            TextInput::make('value_text')
                ->label('Keterangan Tambahan')
                ->placeholder('Contoh: kronis, sejak 2020, ringan, dsb')
                ->columnSpan(3),
        ]);
}


    /* =========================
     * TABLE
     * ========================= */
    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('detail_problem')
                    ->label('Detail Diagnosis')
                    ->html()
                    ->wrap()
                    ->state(fn ($record) => $this->renderDiagnosis($record)),

                TextColumn::make('created_at')
                    ->label('Tanggal Input')
                    ->dateTime('d M Y H:i'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Diagnosis')
                    ->modalWidth('7xl')
                    ->createAnother(false)
                    ->visible(fn ($livewire) =>
                        $livewire->ownerRecord->diagnoses()->count() === 0
                    ),
            ])
            ->recordActions([
                // \Filament\Actions\Action::make('pdf')
                //     ->label('PDF')
                //     ->icon('heroicon-o-document-arrow-down')
                //     ->url(fn ($record) => route('diagnosis.resume.pdf', $record))
                //     ->openUrlInNewTab(),

                EditAction::make()->modalWidth('7xl'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    /* =========================
     * RENDERER
     * ========================= */
    protected function renderDiagnosis($record): HtmlString
    {
        $output = [];

        foreach ($record->components->groupBy('variable') as $variable => $components) {
            $seen = [];
            $items = [];

            foreach ($components as $component) {
                foreach ($component->values as $value) {
                    if (! in_array($value->snomed_concept_id, $seen)) {
                        $items[] = "• {$value->snomed_fsn}";
                        $seen[] = $value->snomed_concept_id;
                    }
                }
            }

            if ($items) {
                $output[] = "<strong>{$variable}</strong>";
                $output = array_merge($output, $items);
                $output[] = '';
            }
        }

        return new HtmlString(implode('<br>', $output));
    }
}
