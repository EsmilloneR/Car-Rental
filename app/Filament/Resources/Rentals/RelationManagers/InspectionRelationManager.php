<?php

namespace App\Filament\Resources\Rentals\RelationManagers;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InspectionRelationManager extends RelationManager
{
    protected static string $relationship = 'inspections';

    /**
     *
        'rental_id',
        'type',
        'fuel_level_in',
        'fuel_level_out',
        'odometer',
        'condition_notes',
        'photos'
     *
     */


    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('type')->options([
                    'in' => 'Check in',
                    'out' => 'Check out',
                ])
                ->required()
                ->reactive()
                ->live()
                ->inlineLabel()
                ->label('Current Status'),

                // TextInput::make('fuel_level_out')
                //     ->inlineLabel()
                //     ->label('Fuel Level (Out)')
                //     ->numeric()
                //     ->prefix('%')
                //     ->visible(fn (callable $get) => $get('type') === 'out')
                //     ->required(fn (callable $get) => $get('type') === 'out'),

                // TextInput::make('fuel_level_in')
                //     ->inlineLabel()
                //     ->label('Fuel Level (In)')
                //     ->numeric()
                //     ->prefix('%')
                //     ->visible(fn (callable $get) => $get('type') === 'in')
                //     ->required(fn (callable $get) => $get('type') === 'in'),


                // TextInput::make('odometer'),

                FileUpload::make('photos')
                    ->multiple()
                    ->image()
                    ->directory('inspections')
                    ->columnSpanFull()
                    ->label('Photos'),
                Textarea::make('condition_notes')
                ->maxLength(500)
                ->columnSpanFull()
                ->label('Condition Notes'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('type')
            ->columns([
                TextColumn::make('type')
                    ->badge()
                    ->colors([
                        'warning' => 'out',
                        'success' => 'in',
                    ])
                    ->formatStateUsing(function ($state) {
                                return match ($state) {
                                    'in' => 'Check In',
                                    'out' => 'Check Out',
                                    default => ucfirst(str_replace('_', ' ', $state)),
                                };
                            })
                    ->label('Current Status'),

                // TextColumn::make('odometer')
                //     ->suffix(' km')
                //     ->sortable(),

                // TextColumn::make('fuel_level_in')
                //     ->suffix('%')
                //     ->sortable(),
                // TextColumn::make('fuel_level_out')
                //     ->suffix('%')
                //     ->sortable(),

                ImageColumn::make('photos')
                    ->imageHeight(40)
                    ->circular()
                    ->stacked(),

                TextColumn::make('created_at')
                    ->dateTime('M d, Y h:i A')
                    ->label('Inspected At')
                    ->sortable(),
        ])->headerActions([
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
