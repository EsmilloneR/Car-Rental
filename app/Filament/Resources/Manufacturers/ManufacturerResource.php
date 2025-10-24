<?php

namespace App\Filament\Resources\Manufacturers;

use App\Filament\Resources\Manufacturers\Pages\CreateManufacturer;
use App\Filament\Resources\Manufacturers\Pages\EditManufacturer;
use App\Filament\Resources\Manufacturers\Pages\ListManufacturers;
use App\Filament\Resources\Manufacturers\Schemas\ManufacturerForm;
use App\Filament\Resources\Manufacturers\Tables\ManufacturersTable;
use App\Models\Manufacturer;
use BackedEnum;
use Filament\Actions\CreateAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;
class ManufacturerResource extends Resource
{
    protected static ?string $model = Manufacturer::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPlusCircle;
    protected static string|BackedEnum|null $activeNavigationIcon = Heroicon::PlusCircle;
    protected static string | UnitEnum | null $navigationGroup = 'Fleet Management';

    protected static ?int $navigationSort = 6;
    protected ?string $heading = 'Vehicle Manufacturers';
    protected ?string $subheading = 'Manage and maintain the list of vehicle manufacturers available in your system.';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ManufacturerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ManufacturersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListManufacturers::route('/'),
            // 'create' => CreateManufacturer::route('/create'),
            // 'edit' => EditManufacturer::route('/{record}/edit'),
        ];
    }

    protected function getHeaderActions(): array
{
    return [
        CreateAction::make()
            ->label('Add New Manufacturer')
            ->icon('heroicon-o-plus')
            ->color('danger')
            ->button()
            ->modalHeading('Register Manufacturer')
            ->modalDescription('Fill out the details below to add a new user to your list.')
            ->createAnother(false),
    ];
}
}
