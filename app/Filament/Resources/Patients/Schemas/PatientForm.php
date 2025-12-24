<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('medical_record_number')
                    ->label('No Rekam Medis')
                    ->required()
                    ->unique(ignoreRecord: true),

                                
                TextInput::make('no_ktp')
                    ->label('No KTP')
                    ->required(),

                TextInput::make('no_bpjs')
                    ->label('No BPJS')
                    ->required(),

                TextInput::make('name')
                    ->label('Nama Pasien')
                    ->required(),

                DatePicker::make('birth_date')
                    ->label('Tanggal Lahir'),

                Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
                    ])
                    ->required(),

                TextInput::make('phone')
                    ->label('No HP'),

                Textarea::make('address')
                    ->label('Alamat'),
            ]);
    }
}
