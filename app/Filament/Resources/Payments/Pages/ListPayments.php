<?php

namespace App\Filament\Resources\Payments\Pages;

use App\Filament\Resources\Payments\PaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;


    public function getHeading(): string
    {
        return 'Payment Reports';
    }

    public function getSubheading(): ?string
    {
        return 'Analyze and review all completed transactions and payment activities.';
    }
}
