<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Models\Vehicle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make()->schema([
                    Group::make([
                        Select::make('manufacturer_id')
                            ->required()
                            ->relationship('manufacturer', 'brand')
                            ->searchable()
                            ->preload(),

                        TextInput::make('model')
                            ->required()
                            ->maxLength(100),
                    ])->columns(2),
                ]),

                Section::make()->schema([
                    Group::make([
                        TextInput::make('year')
                            ->required(),
                        TextInput::make('plate_number')
                            ->required()->unique(Vehicle::class, 'plate_number', ignoreRecord: true),
                    ])->columns(2)
                ]),

                Section::make()->schema([
                    Group::make([
                        TextInput::make('color')
                            ->default(null),
                        Select::make('transmission')
                            ->options(['automatic' => 'Automatic', 'manual' => 'Manual'])
                            ->required()
                            ->default('automatic'),
                        TextInput::make('seats')
                            ->required()
                            ->default('5'),

                    ])->columns(3),
                ]),

                Section::make()->schema([
                    TextInput::make('rate_hour')
                        ->numeric()
                        ->prefix('₱')
                        ->required()
                        ->minValue(1)
                        ->maxValue(99999999.99)
                        ->step(0.01)
                        ->placeholder('250.00'),
                    TextInput::make('rate_day')
                        ->numeric()
                        ->prefix('₱')
                        ->required()
                        ->minValue(1)
                        ->maxValue(99999999.99)
                        ->step(0.01)
                        ->placeholder('500.00'),
                    // TextInput::make('rate_week')
                    //     ->numeric()
                    //     ->prefix('₱')
                    //     ->required()
                    //     ->minValue(1)
                    //     ->maxValue(99999999.99)
                    //     ->step(0.01)
                    //     ->placeholder('1000.00'),
                ])
                ->columns(2),

                Section::make()->schema([
                    Toggle::make('active')
                        ->required()
                        ->default(true),
                    MarkdownEditor::make('description')
                    ->columnSpanFull()
                    ->fileAttachmentsDirectory('vehicles'),
                ])->columnSpanFull(),

                Section::make('Images')->schema([
                    Group::make([
                        FileUpload::make('avatar')
                            ->directory('vehicles_avatar')
                            ->uploadingMessage('Uploading attachment...')
                            ->visibility('public')
                            ->disk('public')
                            ->required(),

                            FileUpload::make('photos')
                            ->required()
                            ->multiple()
                            ->visibility('public')
                            ->disk('public')
                            ->label('Photos')
                            ->directory('vehicles_photos')
                            ->reorderable()
                            ->appendFiles()
                            ->uploadingMessage('Uploading attachment...'),
                    ])
                    ->columns(2),

                ])->columnSpanFull()->collapsible(),

            ]);
    }
}
