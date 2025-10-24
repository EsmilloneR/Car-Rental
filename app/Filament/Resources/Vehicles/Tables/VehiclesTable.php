<?php

namespace App\Filament\Resources\Vehicles\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    ImageColumn::make('avatar')
                        ->imageHeight(50)
                        ->imageWidth(80)
                        ->grow(false)
                        ->visibility('public')
                        ->disk('public'),
                    Stack::make([
                        TextColumn::make('manufacturer.brand')
                            ->searchable()
                            ->sortable()
                            ->grow(false),
                        TextColumn::make('model')
                            ->searchable()
                            ->sortable()
                            ->grow(false),
                    ]),


                    Stack::make([
                        TextColumn::make('plate_number')
                            ->searchable()
                            ->sortable()
                            ->grow(false),
                        TextColumn::make('active')
                            ->label('Status')
                            ->formatStateUsing(fn (bool $state): string => $state ? 'Active' : 'Inactive')
                            ->badge()
                            ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                            ->grow(false),
                    ]),
                ]),

            ])
            ->filters([
                TernaryFilter::make('active')
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                ]),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
