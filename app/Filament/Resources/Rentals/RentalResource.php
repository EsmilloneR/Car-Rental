<?php

namespace App\Filament\Resources\Rentals;

use App\Filament\Resources\Rentals\Pages\CreateRental;
use App\Filament\Resources\Rentals\Pages\EditRental;
use App\Filament\Resources\Rentals\Pages\ListRentals;
use App\Filament\Resources\Rentals\RelationManagers\InspectionRelationManager;
use App\Filament\Resources\Rentals\Schemas\RentalForm;
use App\Filament\Resources\Rentals\Tables\RentalsTable;
use App\Filament\Resources\Rentals\Widgets\RentalChart;
use App\Filament\Resources\Rentals\Widgets\RentalStatsOverview;
use App\Models\Rental;
use BackedEnum;
use UnitEnum;

use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RentalResource extends Resource
{
    protected static ?string $model = Rental::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDateRange;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::CalendarDateRange;
    protected static string | UnitEnum | null $navigationGroup = 'Report Management';

    // protected static ?string $navigationBadgeTooltip = 'The number of renters';

    protected static ?string $recordTitleAttribute = 'agreement_no';

    protected static ?int $navigationSort = 4;
    public static function form(Schema $schema): Schema
    {
        return RentalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RentalsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            InspectionRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRentals::route('/'),
            'create' => CreateRental::route('/create'),
            'edit' => EditRental::route('/{record}/edit'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            RentalChart::class,
            RentalStatsOverview::class
        ];
    }


    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }


    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of users';
    }

    
}
