<?php

namespace App\Filament\Resources\Rentals\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RentalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->groups([
                'status',
                'user.name',
            ])

            ->columns([
                Split::make([
                    Stack::make([
                        TextColumn::make('user.name')
                            ->label('Renter')
                            ->searchable()
                            ->sortable(),

                        TextColumn::make('agreement_no')
                            ->weight('bold')
                            ->searchable()
                            ->sortable(),
                    ])->space(1),

                    Stack::make([
                        TextColumn::make('vehicle.make')
                            ->label('Vehicle')
                            ->getStateUsing(fn ($record) => "{$record->vehicle->make} {$record->vehicle->model}")
                            ->searchable()
                            ->sortable(),

                        TextColumn::make('vehicle.plate_number')
                            ->label('Licensed Number')
                            ->searchable()
                            ->sortable()
                            ->weight('bold'),
                    ]),

                    Stack::make([
                        TextColumn::make('status')
                            ->formatStateUsing(fn ($state) => ucwords(str_replace('_', ' ', $state)))
                            ->searchable()
                            ->sortable()
                            ->color([
                                'warning' => 'pending',
                                'primary' => 'reserved',
                                'success' => 'ongoing',
                                'info' => 'completed',
                                'danger' => 'cancelled',
                            ]),

                        TextColumn::make('total')
                            ->money('php')
                            ->weight('bold'),

                        // ✅ Added computed Remaining Balance
                        TextColumn::make('remaining_balance')
                            ->label('Remaining Balance')
                            ->money('php')
                            ->sortable()
                            ->color('warning')
                            ->tooltip('Automatically computed: Base Amount minus Reservation Fee'),
                    ]),

                    Stack::make([
                        TextColumn::make('rental_start')
                            ->dateTime()
                            ->label('Rent Start')
                            ->sortable(),

                        TextColumn::make('rental_end')
                            ->dateTime()
                            ->label('Rent End')
                            ->sortable()
                            ->weight('bold'),
                    ]),
                ]),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'reserved' => 'Reserved',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])

            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
