<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListUsers extends ListRecords
{
    protected static string $resource = UserResource::class;

     public function getHeading(): string
    {
        return 'User Management';
    }

    public function getSubheading(): ?string
    {
        return 'Manage system users, assign roles, and update account information.';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Add New User')
                ->icon('heroicon-o-user-plus')
                ->color('primary')
                ->button()
                ->modalHeading('Register User')
                ->modalDescription('Fill out the details below to add a new user to your list.')
                ->createAnother(false),
        ];
    }
}
