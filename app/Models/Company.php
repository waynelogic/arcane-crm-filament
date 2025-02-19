<?php namespace App\Models;

use App\Service\Api\DaData;
use App\Service\Api\UIAvatar;
use App\Service\Database\Traits\HasExternalId;
use App\Service\Database\Traits\Manageable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends FileModel
{
    use HasExternalId, Manageable;

    protected $table = 'companies';

    protected $appends = ['logo'];
    protected $casts = [
        'is_customer' => 'boolean',
        'is_supplier' => 'boolean',
        'active' => 'boolean',
        'inn' => 'integer',
        'kpp' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        self::created(function ($model) {
            DaData::fillCompany($model, true);
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('company_logos')
            ->singleFile();
    }

    public function getLogoAttribute(): ?string
    {
        return $this->getFirstMediaUrl('company_logos');
    }

    public function generateLogo(): void
    {
        $generatedAvatarUrl = UIAvatar::make($this->name)->get();

        if ($this->hasMedia('company_logos')) {
            $this->clearMediaCollection('company_logos');
        }
        $this->addMediaFromUrl($generatedAvatarUrl)->toMediaCollection('company_logos');
    }
    public function contacts() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'company_users', 'company_id', 'user_id')
            ->withPivot(['position', 'role'])
            ->withTimestamps();
    }
}
