<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Section::make([
                        TextInput::make('name')
                            ->validationAttribute('Name')
                            ->required(),

                        Group::make([
                            TextInput::make('email')
                                ->label('Email address')
                                ->email()
                                ->maxLength(255)
                                ->required()
                                ->unique(User::class, 'email', ignoreRecord: true),
                            TextInput::make('phone_number')
                                ->tel()->required()->unique(User::class, 'phone_number', ignoreRecord: true)->rules(['required', 'digits:10', 'numeric'])
                                ->helperText('Enter a 10-digits phone number(numbers only, no spaces or symbols)')
                                ->validationAttribute('phone number'),
                        ])->columns(2),


                        Group::make([
                            TextInput::make('password')->dehydrated(fn($state) => filled($state))
                            ->password()
                            ->required(fn(Page $livewire): bool => $livewire instanceof CreateRecord)
                            ->autocomplete('new-password'),

                            DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->default(now())
                            ->disabled()
                            ,
                        ])->columns(2),

                    ]),
                ]),

                Group::make([
                    Section::make([
                        Select::make('role')
                            ->options(['renter' => 'Renter', 'admin' => 'Admin', 'super-admin' => 'Super admin'])
                            ->default('renter'),

                        Group::make([
                            TextInput::make('address')
                                ->default(null),
                            TextInput::make('nationality')
                                ->default('Filipino'),
                        ])->columns(2),

                        Group::make([
                        Select::make('id_type')
                        ->options([
                            'passport' => 'Passport',
                            'driver_license' => 'Driver License',
                            'national_id' => 'National ID',
                            'others' => 'Others'
                        ])
                        ->label('ID Type')
                        ->required(),
                            TextInput::make('id_number')->label('ID Number')
                                ->default(null)->unique(User::class, 'id_number', ignoreRecord: true)->required(),
                        ])->columns(2),

                    ]),
                ]),


                Group::make([
                    Section::make([
                        FileUpload::make('avatar')
                            ->label('Profile Photo')
                            ->directory('users_avatar')
                            ->disk('public')
                            ->visibility('public')
                            ->image()
                            ->maxSize(2048)
                            ->uploadingMessage('Uploading attachment...'),

                        FileUpload::make('id_pictures')
                            ->multiple()
                            ->label('ID Picture - (Front & Back)')
                            ->directory('user_id')
                            ->reorderable()
                            ->disk('public')
                            ->visibility('public')
                            ->minFiles(1)
                            ->maxFiles(2)
                            ->appendFiles()
                            ->uploadingMessage('Uploading attachment...')


                    ])->columns(2),
                ])->columnSpan(2),

            ]);
    }
}
