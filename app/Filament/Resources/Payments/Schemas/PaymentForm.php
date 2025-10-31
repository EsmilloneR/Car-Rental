<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Payment;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('rental_id')
                ->relationship('rentals', 'agreement_no')->required()->label('Rental Agreement')->required()
                ->searchable()
                ->preload()
                ->disabled()
                ->dehydrated(),

                TextInput::make('amount')
                    ->numeric()
                    ->required()
                    ->disabled()

                    ->label('Amount')
                    ->prefix('₱')
                    ->minValue(0),

                TextInput::make('payment_method')
                    ->label('Payment Method')
                    ->visible(fn($get) => $get('payment_method') !== 'cash')
                    ->maxLength(255)
                    ->disabled()
                    ->dehydrated(),

                Select::make('status')
                    ->options([
                        Payment::STATUS_PENDING   => 'Pending',
                        Payment::STATUS_COMPLETED => 'Completed',
                        Payment::STATUS_FAILED    => 'Failed',
                    ])
                    ->default(Payment::STATUS_PENDING)
                    ->required()
                    ->label('Status'),
            ]);
    }
}
