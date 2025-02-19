<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Gender;
use App\Service\Api\UIAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, InteractsWithMedia;

    protected $appends = ['avatar'];
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'birthday',
        'phone',
        'address',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthday' => 'date',
            'gender' => Gender::class,
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function (User $model) {
            if (!$model->hasMedia('avatar')) {
                $model->generateAvatar();
            }
        });
    }

    public function generateAvatar(): void
    {
        $avatarUrl = UIAvatar::make($this->name)->get();
        $this->addMediaFromUrl($avatarUrl)->toMediaCollection('avatar');
    }

    public function getAvatarAttribute(): ?string
    {
        return $this->getFirstMediaUrl('avatar');
    }

    public function scopeOfCompany($query, $company_id = null)
    {
        $company_id  = $company_id ?? session()->get('current_company', auth()->user()->companies()->first()->id);

        return $query->join('company_users', 'users.id', '=', 'company_users.user_id')
            ->where('company_users.company_id', $company_id)
            ->select('users.*');
    }

    public function companies() : BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_users', 'user_id', 'company_id')
            ->withPivot(['position', 'role'])
            ->withTimestamps();
    }
}
