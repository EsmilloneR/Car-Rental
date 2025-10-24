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
                    ->relationship('user', 'name', modifyQueryUsing: fn ($query) => $query->where('role', 'renter'))
                    ->required()
                    ->preload()
                    ->disabled()
                    ->dehydrated()
                    ->live(),

                    // Select::make('vehicle_id')
                    //     ->label('Vehicle')
                    //     ->relationship(
                    //         name: 'vehicle',
                    //         titleAttribute: 'model',
                    //         modifyQueryUsing: function ($query, callable $get) {
                    //             $start = $get('rental_start');
                    //             $end   = $get('rental_end');

                    //             if ($start && $end) {
                    //                 $query->whereDoesntHave('rentals', function ($q) use ($start, $end) {
                    //                     $q->whereIn('status', ['reserved', 'ongoing']) // block active rentals
                    //                     ->where(function ($q2) use ($start, $end) {
                    //                         $q2->whereBetween('rental_start', [$start, $end])
                    //                             ->orWhereBetween('rental_end', [$start, $end])
                    //                             ->orWhere(function ($q3) use ($start, $end) {
                    //                                 $q3->where('rental_start', '<=', $start)
                    //                                     ->where('rental_end', '>=', $end);
                    //                             });
                    //                     });
                    //                 });
                    //             }

                    //             $query->whereDoesntHave('rentals', function ($q) {
                    //                 $q->whereIn('status', ['reserved', 'ongoing']);
                    //             });
                    //         }
                    //     )
                    //     ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->manufacturer->name} {$record->model} {$record->year}")
                    //     ->required()
                    //     ->searchable()
                    //     ->preload(),

                    DateTimePicker::make('rental_start')
                        ->label('Start Date/Time')
                        ->required(),
                    DateTimePicker::make('rental_end')
                        ->label('End Date/Time')
                        ->required(),

                    TextInput::make('pickup_location')
                        ->default('Davao City')
                        ->nullable(),
                    TextInput::make('dropOff_location')
                        ->default('Cagayan De Oro City')
                        ->nullable(),

                    Select::make('trip_type')
                        ->options([
                        'pickup_dropOff' => 'Pickup drop off',
                        'hrs' => 'Hrs',
                        'roundtrip' => 'Roundtrip',
                        '24hrs' => '24hrs',
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
                Section::make()->schema([
                    TextInput::make('base_amount')
                        ->required()
                        ->numeric()
                        ->default(0.0),
                    TextInput::make('deposit')
                        ->required()
                        ->numeric()
                        ->default(0.0),
                    TextInput::make('extra_charges')
                        ->required()
                        ->numeric()
                        ->default(0.0),
                    TextInput::make('penalties')
                        ->required()
                        ->numeric()
                        ->default(0.0),

                ]),

            ]);
    }
}
