<?php

namespace App\Filament\Resources\Patients\RelationManagers;

use App\Services\SnomedService;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
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

            Repeater::make('components')
                ->label('Komponen Diagnosis (Problem)')
                ->relationship()
                ->schema([

                    Hidden::make('component')
                        ->default('Problem')
                        ->dehydrated(),

                    Select::make('variable')
                        ->label('Variable')
                        ->options([
                            'Keluhan Utama'      => 'Keluhan Utama',
                            'Riwayat Penyakit'   => 'Riwayat Penyakit',
                            'Tindakan dan Hasil' => 'Tindakan dan Hasil',
                            'Diagnosa'           => 'Diagnosa',
                            'Obat'               => 'Obat',
                        ])
                        ->required(),

                    Repeater::make('values')
                        ->label('Daftar SNOMED / Nilai')
                        ->relationship()
                        ->schema([

                            Select::make('snomed_concept_id')
                                ->label('Cari SNOMED CT')
                                ->searchable()
                                ->getSearchResultsUsing(
                                    fn (string $search): array =>
                                        app(SnomedService::class)->searchByTerm($search)
                                )
                                ->getOptionLabelUsing(
                                    fn ($value) =>
                                        $value
                                            ? app(SnomedService::class)->formatLabel($value)
                                            : null
                                )
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if (! $state) {
                                        return;
                                    }

                                    $label = app(SnomedService::class)->formatLabel($state);

                                    if (preg_match('/^(.*) \(/', $label, $m)) {
                                        $set('snomed_fsn', $m[1]);
                                    }
                                })
                                ->required(),

                            TextInput::make('snomed_fsn')
                                ->label('SNOMED Term')
                                ->disabled()
                                ->dehydrated(),

                            TextInput::make('value_text')
                                ->label('Keterangan Tambahan')
                                ->placeholder('Opsional'),
                        ])
                        ->columns(2)
                        ->collapsible()
                        ->addActionLabel('Tambah Keluhan / Diagnosis'),
                ])
                ->collapsible()
                ->addActionLabel('Tambah Variable Problem'),

            Textarea::make('diagnosis_text')
                ->label('Ringkasan Diagnosis Dokter')
                ->rows(2)
                ->required(),
        ]);
    }

    /* =========================
     * TABLE
     * ========================= */
    public function table(Table $table): Table
{
    return $table
        ->columns([

            TextColumn::make('diagnosis_text')
                ->label('Ringkasan Diagnosis')
                ->limit(40)
                ->wrap(),

            TextColumn::make('detail_problem')
                ->label('Detail Problem')
                ->state(function ($record) {
                 $output = [];
        
                foreach ($record->components->groupBy('variable') as $variable => $components) {
                 $values = [];
                 $seenIds = [];
            
                foreach ($components as $component) {
                foreach ($component->values as $value) {
                    if (!in_array($value->snomed_concept_id, $seenIds)) {
                        $values[] = "• {$value->snomed_fsn}";
                        $seenIds[] = $value->snomed_concept_id;
                    }
                }
            }
            
            if (!empty($values)) {
                $output[] = "<strong>{$variable}</strong>";
                $output = array_merge($output, $values);
                $output[] = ""; // spacing
            }
        }
        
        return new \Illuminate\Support\HtmlString(implode("<br>", $output));
    })
    ->wrap()
    ->html(),

            TextColumn::make('created_at')
                ->label('Tanggal')
                ->dateTime('d M Y H:i'),
        ])
        ->recordActions([

            \Filament\Actions\Action::make('pdf')
                ->label('PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn ($record) => route('diagnosis.resume.pdf', $record))
                ->openUrlInNewTab(),

            EditAction::make(),
            DeleteAction::make(),
        ])
        ->headerActions([
            CreateAction::make(),
        ])
        ->toolbarActions([
            BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ])
        ->defaultSort('created_at', 'desc');
}

}
