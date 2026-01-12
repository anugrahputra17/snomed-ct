<?php

namespace App\Filament\Resources\Patients\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;

class PatientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('medical_record_number')
                    ->label('No Rekam Medis')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('no_bpjs')
                    ->label('No BPJS')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama Pasien')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('gender')
                    ->label('Jenis Kelamin')
                    ->formatStateUsing(fn (?string $state) => match ($state) {
                        'male'   => 'Laki-laki',
                        'female' => 'Perempuan',
                        default  => '-',
                    }),

                TextColumn::make('birth_date')
                    ->label('Tanggal Lahir')
                    ->date('d M Y'),

                TextColumn::make('phone')
                    ->label('No HP')
                    ->toggleable(isToggledHiddenByDefault: true),

                // TextColumn::make('created_at')
                //     ->label('Tanggal Input')
                //     ->dateTime('d M Y H:i')
                //     ->sortable(),
            ])

            ->filters([
                // nanti bisa tambah filter gender, umur, dll
            ])

            ->recordActions([
                ViewAction::make()
                    ->label('Detail'),

                EditAction::make()
                    ->label('Edit'),

                Action::make('resume')
                    ->label('Resume Medis PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->url(function ($record) {
                        $diagnosis = $record->diagnoses()
                            ->latest()
                            ->first();

                        if (! $diagnosis) {
                            return null;
                        }

                        return route('diagnosis.resume.pdf', $diagnosis);
                    })
                    ->openUrlInNewTab()
                    ->visible(fn ($record) => $record->diagnoses()->exists()),

            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->label('Hapus Terpilih'),
                ]),
            ])

            ->defaultSort('created_at', 'desc');
    }
}
