<?php

namespace App\Filament\Resources\Rentals\Schemas;

use App\Models\Rental;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Icon;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class RentalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Rental Information')->schema([

                    TextInput::make('agreement_no')
                        ->disabled()
                        ->dehydrated()
                        ->default(function () {
                            $date = now()->format('Ymd');
                            $count = Rental::whereDate('created_at', now())->count() + 1;
                            return 'AGR-' . $date . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
                        }),

                    Select::make('user_id')
                        ->relationship('user', 'name', modifyQueryUsing: fn($query) => $query->where('role', 'renter'))
                        ->required()
                        ->preload()
                        ->disabled()
                        ->dehydrated()
                        ->live(),

                    DateTimePicker::make('rental_start')
                        ->label('Start Date/Time')
                        ->required(),

                    DateTimePicker::make('rental_end')
                        ->label('End Date/Time')
                        ->required(),

                    Select::make('trip_type')
                        ->options([
                            'pickup_dropOff' => 'Pickup & Drop-off',
                            'hrs' => 'Hourly',
                            'roundtrip' => 'Roundtrip',
                            '24hrs' => '24 Hours',
                            'days' => 'Days',
                            'weeks' => 'Weeks',
                            'months' => 'Months',
                        ])
                        ->required()
                        ->live(),

                    Select::make('status')
                        ->beforeLabel(Icon::make(Heroicon::Star))
                        ->options([
                            'pending' => 'Pending',
                            'reserved' => 'Reserved',
                            'ongoing' => 'Ongoing',
                            'completed' => 'Completed',
                            'cancelled' => 'Cancelled',
                        ])
                        ->default('pending')
                        ->required()
                        ->live(),

                ])->columns(2),

                Section::make('Payment Details')->schema([
                    TextInput::make('base_amount')
                        ->label('Base Amount (₱)')
                        ->required()
                        ->numeric()
                        ->default(0.0)
                        ->reactive()
                        ->prefix('₱'),

                    TextInput::make('reservation_fee')
                        ->label('Reservation Fee (₱)')
                        ->required()
                        ->numeric()
                        ->default(0.0)
                        ->reactive()
                        ->prefix('₱')
                        ->afterStateUpdated(function (callable $set, $get) {
                            $remaining = max(0, ($get('base_amount') ?? 0) - ($get('reservation_fee') ?? 0));
                            $set('remaining_balance', $remaining);
                        })
                        ->hint('Automatically computed: ₱1000 or 20% of base amount.'),

                    TextInput::make('extra_charges')
                        ->label('Extra Charges (₱)')
                        ->required()
                        ->numeric()
                        ->default(0.0)
                        ->reactive()
                        ->prefix('₱'),

                    TextInput::make('penalties')
                        ->label('Penalties (₱)')
                        ->required()
                        ->numeric()
                        ->default(0.0)
                        ->reactive()
                        ->prefix('₱'),

                    TextInput::make('remaining_balance')
                        ->label('Remaining Balance (₱)')
                        ->disabled()
                        ->dehydrated(false)
                        ->reactive()
                        ->prefix('₱')
                        ->afterStateHydrated(function ($set, $get) {
                            $remaining = max(0, ($get('base_amount') ?? 0) - ($get('reservation_fee') ?? 0));
                            $set('remaining_balance', $remaining);
                        }),
                ])
                ->columns(2)
                ->afterStateUpdated(function (callable $set, $get) {
                    $base = (float) $get('base_amount');
                    $tripType = $get('trip_type') ?? 'days';

                    $duration = 1;
                    if ($get('rental_start') && $get('rental_end')) {
                        $duration = now()->parse($get('rental_end'))->diffInDays(now()->parse($get('rental_start'))) ?: 1;
                    }

                    $fee = Rental::calculateReservationFee($base, $tripType, $duration);
                    $set('reservation_fee', $fee);

                    $remaining = max(0, $base - $fee);
                    $set('remaining_balance', $remaining);
                }),
            ]);
    }
}
