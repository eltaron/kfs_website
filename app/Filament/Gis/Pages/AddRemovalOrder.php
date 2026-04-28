<?php

namespace App\Filament\Gis\Pages;

use App\Models\RemovalOrder;
use App\Models\GisMarkaz;
use App\Models\GisShiakha;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Get;
use Filament\Notifications\Notification;

class AddRemovalOrder extends Page implements HasForms
{
    
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?string $navigationGroup = 'حوكمة قرارات الإزالة';
    protected static ?string $navigationLabel = 'إضافة قرار إزالة جديد';
    protected static ?string $title = 'نموذج تسجيل مخالفة قرار إزالة';
    protected static ?int $navigationSort = 0; // ليكون أول عنصر في المجموعة

    protected static string $view = 'filament.gis.pages.add-removal-order';
     // 1. هذه الدالة تتحكم في إظهار الرابط في القائمة الجانبية (Sidebar)
    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()->hasAnyRole([
            'super_admin', 
            "مهندس التنظيم",
            "مدير التنظيم",
            'فني التنظيم'
        ]);
    }

    // 2. هذه الدالة تتحكم في صلاحية الدخول للصفحة (تمنع الدخول عبر الرابط المباشر)
    public static function canAccess(): bool
    {
        return auth()->user()->hasAnyRole([
            'super_admin', 
            'فني التنظيم'
        ]);
    }
    // مصفوفة البيانات التي سيتم ربطها بالفورم
    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }


    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                    // المرحلة 1: نوع المخالفة
                    Wizard\Step::make('نوع المخالفة')
                        ->schema([
                            Select::make('violation_type')
                                ->label('اختر نوع المخالفة')
                                ->options([
                                    'new_violation' => 'مخالفة بناء بدون ترخيص',
                                    'licensed_violation' => 'مخالفة شروط ترخيص',
                                ])->required()->live(),

                            Section::make('بيانات الترخيص')
                                ->visible(fn(Get $get) => $get('violation_type') === 'licensed_violation')
                                ->schema([
                                    TextInput::make('license_number')->label('رقم الترخيص')->required(),
                                    DatePicker::make('license_date')->label('تاريخ الترخيص')->required(),
                                ])->columns(2),
                        ]),

                    // المرحلة 2: بيانات الموقع والمالك
                    Wizard\Step::make('الموقع والمالك')
                        ->schema([
                            Grid::make(3)->schema([
                                Select::make('center')->label('المركز')->options(GisMarkaz::pluck('name', 'name'))->required()->live(),
                                Select::make('local_unit')->label('الوحدة المحلية')->options(fn(Get $get) => GisShiakha::whereHas('markaz', fn($q) => $q->where('name', $get('center')))->pluck('name', 'name'))->required(),
                                TextInput::make('street')->label('الشارع')->required(),
                            ]),
                            TextInput::make('owner_name')->label('الاسم الكامل للمالك')->required(),
                            TextInput::make('owner_national_id')->label('الرقم القومي')->length(14)->required(),
                        ]),

                    // المرحلة 3: فنيات المخالفة (والـ GPS)
                    Wizard\Step::make('المعاينة والقرارات')
                        ->schema([
                            RichEditor::make('violation_works')->label('وصف الأعمال المخالفة')->required(),
                            Grid::make(2)->schema([
                                TextInput::make('latitude')->label('خط العرض')->readonly()->id('lat_input'),
                                TextInput::make('longitude')->label('خط الطول')->readonly()->id('lng_input'),
                            ]),
                            Actions::make([
                                Action::make('get_gps')
                                    ->label('التقاط إحداثيات الموقع الحالي')
                                    ->icon('heroicon-m-map-pin')
                                    ->color('danger')
                                    ->extraAttributes(['onclick' => "getUserLocation()"]),
                            ]),
                            FileUpload::make('photo_file')->label('صورة المخالفة')->image()->required(),

                            Grid::make(2)->schema([
                                TextInput::make('stop_order_number')->label('رقم قرار الإيقاف')->required(),
                                DatePicker::make('stop_order_date')->label('تاريخ القرار')->required(),
                            ]),
                        ]),
                ])
                    ->submitAction(view('filament.gis.components.submit-button')) // زر الحفظ النهائي
                    ->statePath('data'),
            ]);
    }

    /**
     * دالة حفظ البيانات النهائية
     */
    public function create()
    {
        $formData = $this->form->getState();

        // إنشاء السجل في جدول RemovalOrder
        RemovalOrder::create($formData);

        Notification::make()
            ->title('تم حفظ قرار الإزالة بنجاح')
            ->success()
            ->send();

        return redirect()->to('/gis/removal-orders'); // العودة للسجل العام
    }
}
