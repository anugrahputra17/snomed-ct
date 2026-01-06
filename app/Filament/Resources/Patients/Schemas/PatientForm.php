<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Get;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('medical_record_number')
                    ->label('No Rekam Medis')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->disabled()
                    ->dehydrated()
                    ->default(fn () => 'RM' . now()->format('Ymd') . mt_rand(1000, 9999)),

                TextInput::make('no_bpjs')
                    ->label('No BPJS')
                    ->maxLength(13)
                    ->rule('digits:13')
                    ->inputMode('numeric')
                    ->disabledOn('edit')
                    ->nullable(),

                TextInput::make('no_ktp')
                    ->label('No KTP')
                    ->maxLength(16)
                    ->rule('digits:16')
                    ->inputMode('numeric')
                    ->disabledOn('edit')
                    ->required(),

                TextInput::make('name')
                    ->label('Nama Pasien')
                    ->required()
                    ->maxLength(255),

                TextInput::make('birth_place')
                    ->label('Tempat Lahir')
                    ->required()
                    ->maxLength(255),

                DatePicker::make('birth_date')
                    ->label('Tanggal Lahir')
                    ->required()
                    ->native(false)
                    ->displayFormat('d/m/Y')
                    ->maxDate(now())
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $birthDate = \Carbon\Carbon::parse($state);
                            $age = $birthDate->age;
                            $set('age', $age);
                        }
                    }),

                TextInput::make('age')
                    ->label('Umur')
                    ->suffix('tahun')
                    ->disabled()
                    ->dehydrated()
                    ->numeric()
                    ->default(0),

                Select::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'male' => 'Laki-laki',
                        'female' => 'Perempuan',
                    ])
                    ->required()
                    ->native(false),

                Select::make('religion')
                    ->label('Agama')
                    ->options([
                        'islam' => 'Islam',
                        'kristen' => 'Kristen',
                        'katolik' => 'Katolik',
                        'hindu' => 'Hindu',
                        'buddha' => 'Buddha',
                        'konghucu' => 'Konghucu',
                    ])
                    ->required()
                    ->native(false),

                TextInput::make('ethnicity')
                    ->label('Suku')
                    ->required()
                    ->maxLength(100)
                    ->placeholder('Contoh: Jawa, Sunda, Batak, dll'),

                Select::make('nationality')
                    ->label('Kewarganegaraan')
                    ->options([
                        'wni' => 'WNI (Warga Negara Indonesia)',
                        'wna' => 'WNA (Warga Negara Asing)',
                    ])
                    ->required()
                    ->native(false)
                    ->default('wni'),

                TextInput::make('phone')
                    ->label('No HP')
                    ->tel()
                    ->maxLength(15)
                    ->placeholder('08xxxxxxxxxx'),

                Textarea::make('address')
                    ->label('Alamat')
                    ->rows(3)
                    ->maxLength(500),
            ]);
    }
}