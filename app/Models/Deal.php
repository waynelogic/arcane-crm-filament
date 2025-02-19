<?php namespace App\Models;
use App\Enums\DealStatus;
use App\Service\Database\Traits\HasExternalId;
use App\Service\Database\Traits\Manageable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Deal extends Model
{
    use HasExternalId, Manageable, HasFactory;

    const EXTERNAL_ID = 'number';

    protected $casts = [
        'status' => DealStatus::class,
        'price' => 'float',
        'total_price' => 'float',
        'total_discount' => 'float',
        'auto_price' => 'boolean',
        'completed' => 'boolean',
        'canceled' => 'boolean',
        'sort_order' => 'integer',
        'deadline' => 'datetime',
        'product_items' => 'array',
    ];
    protected static function boot()
    {
        parent::boot();

        self::saving(function ($model) {
            $totalPrice = 0;
            if ($model->product_items) {
                foreach ($model->product_items as $item) {
                    $totalPrice += (float) $item['price'] * (float) $item['quantity'];
                }
            }
            $model->total_price = $totalPrice + $model->price;
        });
    }

    public function parent() : BelongsTo
    {
        return $this->belongsTo(Deal::class, 'parent_id');
    }

    public function customer() : BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function contact() : BelongsTo
    {
        return $this->belongsTo(User::class, 'contact_id');
    }

    public function tasks() : HasMany
    {
        return $this->hasMany(Task::class, 'deal_id');
    }
}
