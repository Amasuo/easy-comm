<?php

namespace App\Models;

use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Order extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;

    protected $table = 'orders';

    const SEARCHABLE = [
        '',
    ];

    protected $fillable = [
        'order_status_id',
        'store_id',
        'customer_id',
        'delivery_company_id',
        'delivery_driver_id',
        'firstname',
        'lastname',
        'phone',
        'state',
        'city',
        'street',
        'chat_link',
        'delivery_comments',
        'internal_comments',
        'delivered_at',
        'created_at'
    ];

    protected $appends = [
        'fullname',
        'price',
        'num_items'
    ];

    protected $with = [
        'order_status',
        'store',
        'customer',
        'delivery_company',
        'delivery_driver',
        'product_variants',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
    ];

    public function getFullnameAttribute(): string
    {
        return $this->firstname ? $this->firstname  . ' ' . $this->lastname : $this->lastname;
    }

    public function getPriceAttribute()
    {
        $price = 0;
        foreach($this->product_variants as $productVariant) {
            $price += $productVariant->price;
        }
        return $price;
    }
    public function getNumItemsAttribute()
    {
        return count($this->product_variants);
    }

    public function order_status(): BelongsTo
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function delivery_company(): BelongsTo
    {
        return $this->belongsTo(DeliveryCompany::class, 'delivery_company_id');
    }

    public function delivery_driver(): BelongsTo
    {
        return $this->belongsTo(DeliveryDriver::class, 'delivery_driver_id');
    }

    public function product_variants(): BelongsToMany
    {
        return $this->belongsToMany(ProductVariant::class, 'order_product_variant')
        ->withPivot([
            'count'
        ]);
    }
}
