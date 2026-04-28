<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
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
use Illuminate\Support\Facades\Blade;

class GisPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('gis')
            ->path('gis')
            ->login()
            ->brandName('بوابة كفر الشيخ الجيومكانية')
            ->brandLogo(asset('logo.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('favicon/favicon.ico'))

            // 1. إعدادات الألوان (الهوية الرسمية: كحلي ذهبي)
            ->colors([
                'primary' => [
                    50 => '238, 242, 255',
                    100 => '224, 231, 255',
                    200 => '199, 210, 254',
                    300 => '165, 180, 252',
                    400 => '129, 140, 248',
                    500 => '30, 39, 46',   // الكحلي الداكن (الأساسي)
                    600 => '25, 33, 39',
                    700 => '225, 177, 44', // الذهبي الملكي (للأزرار والتركيز)
                    800 => '30, 64, 175',
                    900 => '30, 58, 138',
                    950 => '23, 37, 84',
                ],
            ])

            // 2. إعدادات الخطوط واللغة
            ->font('Cairo')
            ->discoverResources(in: app_path('Filament/Gis/Resources'), for: 'App\\Filament\\Gis\\Resources')
            ->discoverPages(in: app_path('Filament/Gis/Pages'), for: 'App\\Filament\\Gis\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])

            // 3. تسجيل الودجات (إحصائيات الإنجاز والخرائط)
            ->discoverWidgets(in: app_path('Filament/Gis/Widgets'), for: 'App\\Filament\\Gis\\Widgets')
            ->widgets([
                \App\Filament\Gis\Widgets\GisPerformanceWidget::class,
                \App\Filament\Gis\Widgets\RemovalStatsWidget::class,
                \App\Filament\Gis\Widgets\ViolationChart::class,
            ])


            // 5. إعدادات الأمان والبحث والواجهة
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
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->sidebarCollapsibleOnDesktop()
            ->databaseNotifications()
            ->profile()
            ->darkMode(true) // دعم الوضع الليلي والنهاري تلقائياً
            ->plugins([
                FilamentShieldPlugin::make()
            ])

            // 6. حقن كود الجافا سكريبت الخاص بالـ GPS (هام جداً لقرارات الإزالة الميدانية)
            ->renderHook(
                'panels::body.end',
                fn(): string => '
                    <script>
                        function getUserLocation() {
                            if (navigator.geolocation) {
                                navigator.geolocation.getCurrentPosition(function(position) {
                                    // ملء الحقول في نموذج Filament
                                    const latInput = document.getElementById("lat_input");
                                    const lngInput = document.getElementById("lng_input");

                                    if (latInput && lngInput) {
                                        latInput.value = position.coords.latitude;
                                        lngInput.value = position.coords.longitude;

                                        // تنبيه المواطن/الموظف بالنجاح
                                        alert("تم التقاط إحداثيات الموقع الحالية بنجاح");

                                        // إرسال حدث لتحديث البيانات في Alpine.js إذا لزم الأمر
                                        latInput.dispatchEvent(new Event("input"));
                                        lngInput.dispatchEvent(new Event("input"));
                                    }
                                }, function(error) {
                                    alert("خطأ: يرجى تفعيل الـ GPS والسماح للمتصفح بالوصول لموقعك.");
                                }, { enableHighAccuracy: true });
                            } else {
                                alert("عفراً، متصفحك لا يدعم خاصية تحديد الموقع.");
                            }
                        }
                    </script>
                '
            );
    }
}
