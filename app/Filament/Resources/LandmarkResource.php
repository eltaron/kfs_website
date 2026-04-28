<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LandmarkResource\Pages;
use App\Models\Landmark;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LandmarkResource extends Resource
{
    protected static ?string $model = Landmark::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';
    protected static ?string $navigationGroup = 'إدارة المحتوى';
    protected static ?string $modelLabel = 'معلم سياحي';
    protected static ?string $pluralModelLabel = 'المعالم السياحية';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('المعلومات الأساسية للمعلم')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('اسم المعلم السياحي')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('location_id')
                            ->relationship('location', 'name')
                            ->label('الموقع الجغرافي (من دليل العاصمة)')
                            ->searchable()
                            ->preload()
                            ->createOptionForm([ // Quick-create a new location from this form
                                Forms\Components\TextInput::make('name')->label('اسم الموقع الجديد')->required(),
                                // ... add latitude/longitude fields if needed
                            ]),
                        Forms\Components\Textarea::make('iframe')
                            ->label('كود تضمين الخريطة أو الجولة الافتراضية (iframe)')
                            ->helperText('إذا كان هناك خريطة مخصصة أو جولة افتراضية، انسخ كود iframe هنا.')
                            ->rows(5)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('thumbnail')
                            ->label('الصورة المصغرة الرئيسية')
                            ->image()
                            ->directory('landmarks/thumbnails')
                            ->required(),

                        Forms\Components\TextInput::make('order')
                            ->label('الترتيب')
                            ->numeric()
                            ->default(0),

                        Forms\Components\RichEditor::make('details')
                            ->label('تفاصيل ومعلومات عن المعلم')
                            ->required()
                            ->columnSpanFull()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'strike',
                                'h2',
                                'h3',
                                'blockquote',
                                'link',
                                'bulletList',
                                'orderedList',
                                'undo',
                                'redo'
                            ]),
                    ])->columns(2),

                Forms\Components\Section::make('معرض صور المعلم')
                    ->schema([
                        Forms\Components\Repeater::make('images')
                            ->relationship()
                            ->label('ألبوم الصور')
                            ->schema([
                                Forms\Components\FileUpload::make('path')
                                    ->label('ملف الصورة')
                                    ->image()
                                    ->directory('landmarks/gallery')
                                    ->required(),
                                Forms\Components\TextInput::make('caption')->label('تعليق (اختياري)'),
                            ])
                            ->addActionLabel('أضف صورة للمعرض')
                            ->collapsible(),
                    ]),
                Forms\Components\Section::make('الموقع الجغرافي (على الخريطة)')
                    ->schema([
                        Forms\Components\TextInput::make('latitude')
                            ->label('خط العرض (Latitude)')
                            ->numeric()
                            ->placeholder('مثال: 31.1143')
                            ->helperText('يمكنك الحصول عليه من جوجل ماب'),

                        Forms\Components\TextInput::make('longitude')
                            ->label('خط الطول (Longitude)')
                            ->numeric()
                            ->placeholder('مثال: 30.9416'),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->label('صورة'),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('location.name')
                    ->label('الموقع')
                    ->searchable()
                    ->sortable()
                    ->placeholder('غير محدد'),

                Tables\Columns\TextColumn::make('order')
                    ->label('الترتيب')
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('آخر تحديث')
                    ->since()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->reorderable('order')
            ->defaultSort('order', 'asc');
    }
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
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
            'index' => Pages\ListLandmarks::route('/'),
            'create' => Pages\CreateLandmark::route('/create'),
            'edit' => Pages\EditLandmark::route('/{record}/edit'),
        ];
    }
}
