<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Models\Payment;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                Split::make([

                    Stack::make([

                        TextColumn::make('rentals.user.name')
                            ->label('Name')
                            ->sortable()
                            ->searchable(),

                        TextColumn::make('rentals.agreement_no')
                            ->label('Agreement No.')
                            ->sortable()
                            ->searchable(),

                    ]),

                    Stack::make([
                        TextColumn::make('payment_method')
                            ->badge()
                            ->sortable()
                            ->formatStateUsing(function ($state) {
                                return match ($state) {
                                    'online_payment' => 'Online Payment',
                                    'cash' => 'Cash',
                                    default => ucfirst(str_replace('_', ' ', $state)),
                                };
                            })
                            ->label('Payment Method'),

                        BadgeColumn::make('status')
                            ->colors([
                                'warning' => Payment::STATUS_PENDING,
                                'success' => Payment::STATUS_COMPLETED,
                                'danger'  => Payment::STATUS_FAILED,
                            ])
                            ->formatStateUsing(fn($state) => ucfirst($state))
                            ->label('Status')
                            ->sortable()
                            ->searchable(),
                    ])->space(1),


                    Stack::make([
                        TextColumn::make('total')
                            ->money('PHP', true)
                            ->sortable()
                            ->label('Amount'),

                        TextColumn::make('created_at')
                            ->dateTime('M d, Y h:i A')
                            ->label('Paid At')
                            ->sortable(),
                    ]),

                ]),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('receipt')
                        ->label('Download Receipt')
                        ->url(fn($record) => route('payments.receipt', $record->id))
                        ->openUrlInNewTab()
                        ->icon('heroicon-o-document-arrow-down'),
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
