<?php

namespace App\Providers\Filament;


use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;

class EstidamaPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('estidama_panal')
            ->path('estidama_panal')
            ->colors([
                Color::Blue // اللون الرمادي Slate يبدو رائعًا مع الذهبي
            ])
            ->login()
            ->brandLogo(asset('logo.png'))
            ->brandLogoHeight('3.5rem')
            ->brandName('KFS ')
            ->favicon(asset('favicon/favicon.ico'))
            ->font('Cairo')
            ->icons([
                'provider' => FontAwesomeIconProvider::class,
            ])
            ->discoverResources(in: app_path('Filament/Estidama/Resources'), for: 'App\\Filament\\Estidama\\Resources')
            ->discoverPages(in: app_path('Filament/Estidama/Pages'), for: 'App\\Filament\\Estidama\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Estidama/Widgets'), for: 'App\\Filament\\Estidama\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
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
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
            ])
            ->sidebarCollapsibleOnDesktop()
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])

            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->profile();
    }
}
