<?php

namespace App\Filament\Resources\Vehicles\RelationManagers;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use location;

class LocationsRelationManager extends RelationManager
{
    protected static string $relationship = 'location';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('latitude')
                    ->required()
                    ->numeric(),
                TextInput::make('longitude')
                    ->required()
                    ->numeric(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
        ->recordTitleAttribute('Location')
            ->columns([
            TextColumn::make('vehicle_id')
                ->numeric()
                ->sortable(),
            TextColumn::make('latitude')
                ->numeric()
                ->sortable(),
            TextColumn::make('longitude')
                ->numeric()
                ->sortable(),
        ])
        ->headerActions([
                CreateAction::make()->createAnother(false),
        ])->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
        ])->bulkActions([
             BulkActionGroup::make([
                DeleteBulkAction::make(),
            ]),
        ]);
    }
}
