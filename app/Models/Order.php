<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
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
        'delivered_at',
    ];

    protected $with = [
        'store',
        'customer',
        'delivery_company',
        'delivery_driver',
        'product_variants',
    ];

    protected $casts = [
        'delivered_at' => 'datetime',
    ];

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
