<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
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
use Illuminate\Session\Middleware\AuthenticateSession;
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
            ->login()
           ->colors([
    'primary' => [
        50  => '250, 232, 255',
        100 => '245, 208, 254',
        200 => '240, 171, 252',
        300 => '232, 121, 249',
        400 => '217, 70, 239',
        500 => '192, 38, 211',
        600 => '162, 28, 175',   // warna tombol utama (light mode)
        700 => '134, 25, 143',
        800 => '112, 26, 117',
        900 => '74, 4, 78',
        950 => '59, 3, 63',
    ],
])
             ->brandName('Pelajarkudus')
            ->brandLogo(fn () => view('filament.brand'))
            ->brandLogoHeight('3.5rem')
            // ->favicon(asset('logo/logo-warna.png'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                \App\Filament\Widgets\PublicSiteCards::class,
                 \App\Filament\Widgets\BlogStatsOverview::class,
                 \App\Filament\Widgets\LatestPostsWidget::class,
                 \App\Filament\Widgets\PostViewsChart::class,
                //  \App\Filament\Widgets\TopPostsWIdget::class,
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
            ]);
    }
}
