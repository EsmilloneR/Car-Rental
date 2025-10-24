<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([
                    ImageColumn::make('avatar')
                        ->circular()
                        ->defaultImageUrl(asset('storage/images/default.jpg'))
                        ->grow(false)
                        ->visibility('public')
                        ->disk('public'),

                    Stack::make([
                        TextColumn::make('name')
                            ->searchable()
                            ->weight(FontWeight::Bold)
                            ->grow(false),
                        TextColumn::make('role')
                            ->badge()
                            ->grow(false),
                    ]),

                    Stack::make([
                        TextColumn::make('email')
                        ->label('Email address')
                        ->searchable(),
                    TextColumn::make('phone_number')
                        ->searchable(),
                    ])
                ]),
            ])
            ->filters([
                //
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
