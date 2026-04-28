<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable, HasRoles, HasUuids, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'national_id',
        'phone',
        'job_title',
        'address',
        'national_id_image',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        // 1. السوبر أدمن يدخل كل اللوحات
        if ($this->hasRole('super_admin')) {
            return true;
        }

        // 2. تحديد الصلاحيات بناءً على معرف اللوحة (Panel ID)
        return match ($panel->getId()) {
            'admin' => $this->hasAnyRole(['super_admin','Admin', 'مسئول اعلام', 'مسئول استثمار ', 'مسئول سياحة', 'مسئول المركز التكنولوجي', 'مسئول التخطيط الاستراتيجي'
            ]),
            'gis'   => $this->hasAnyRole(['super_admin','Admin','مدير المركز', 'مدير الادارة الهندسية', 'مهندس التنظيم', 'مدير التنظيم'
             , 'فني التنظيم' , 'مدير الوحدة الفرعية'
             , 'العضو الميداني' , 'مدخل البيانات بالوحدة الفرعية'
             , 'محللي النظم' , 'الدعم الاداري'
             , 'رؤوساء الاقسام'
            ]),
            'estidama_panal' => $this->hasAnyRole(['super_admin','Admin', 'estidama_trainer', 'estidama_admin']),
            default => false,
        };
    }
    public function comments(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Comment::class);
    }
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->logOnlyDirty()
            ->useLogName('User Actions')
            ->setDescriptionForEvent(fn(string $eventName) => "User account has been {$eventName}")
            ->dontSubmitEmptyLogs();
    }

    public function complaints()
    {
        // foreignKey: 'national_id' on the 'complaints' table
        // localKey: 'national_id' on the 'users' table
        return $this->hasMany(Complaint::class, 'national_id', 'national_id');
    }

    public function suggestions()
    {
        // foreignKey: 'national_id' on the 'suggestions' table
        // localKey: 'national_id' on the 'users' table
        return $this->hasMany(Suggestion::class, 'national_id', 'national_id');
    }
    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')->withTimestamps()->withPivot('awarded_at');
    }
    public function isEmployee(): bool
    {
        // مقارنة الرقم القومي للمستخدم الحالي بالأرقام المسجلة في جدول الموظفين
        return Employee::where('national_id', $this->national_id)->exists();
    }
}
