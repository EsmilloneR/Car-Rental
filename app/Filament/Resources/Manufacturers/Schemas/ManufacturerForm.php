<?php

namespace App\Filament\Resources\Manufacturers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ManufacturerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make()->schema([
                    TextInput::make('brand')
                    ->maxLength(255)
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn(string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                    TextInput::make('slug')
                        ->maxLength(255)
                        ->required()
                        ->unique('manufacturers', 'slug', ignoreRecord: true)
                        ->disabled()
                        ->dehydrated(),
                ])->columns(2),
                Grid::make()
                    ->schema([
                    FileUpload::make('image')
                        ->image()
                        ->directory('manufacturers')
                        ->disk('public')
                        ->visibility('public')
                        ->maxFiles(1)
                        ->required(),
                    ])->columnSpanFull(),

            ]);
    }
}
