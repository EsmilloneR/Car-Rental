<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Analytics;
use App\Filament\Pages\CarTracking;
use App\Filament\Pages\Report;
use App\Filament\Resources\Rentals\Widgets\RentalChart;
use App\Filament\Resources\Rentals\Widgets\RentalStatsOverview;
use App\Http\Middleware\AdminMiddleware;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            // ->login()
            ->databaseNotifications()
            ->profile()
            ->sidebarCollapsibleOnDesktop()
            ->sidebarWidth('15rem')
            ->brandLogo(asset('favicon.ico'))
            ->favicon(asset('favicon.ico'))
            ->brandLogoHeight('3rem')
            ->brandName('Twayne Garage')
            ->colors([
                'primary' => Color::Red,
            ])
            ->navigationItems([
                NavigationItem::make('Home')
                    ->url('/')
                    ->icon('heroicon-o-home')
                    ->sort(-10),

                NavigationItem::make('Paymongo')
                    ->url('https://dashboard.paymongo.com/payments')
                    ->icon('heroicon-o-credit-card')
                    ->sort(-10)
                    ->openUrlInNewTab()
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                // Dashboard::class,
                Report::class,
                CarTracking::class,
                Analytics::class
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
                RentalChart::class,
                RentalStatsOverview::class,
                // Report::class
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                AdminMiddleware::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                AdminMiddleware::class,
            ])

            ->authGuard('web');
    }
}
