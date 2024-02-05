<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryDriver extends Model
{
    use HasFactory;
    protected $table = 'delivery_drivers';

    const SEARCHABLE = [
        'firstname',
        'lastname',
        'phone',
    ];

    protected $with = [
        'delivery_company',
        'store',
    ];

    protected $fillable = [
        'delivery_company_id',
        'store_id',
        'firstname',
        'lastname',
        'phone',
    ];

    protected $appends = [
        'is_private',
        'fullname',
    ];

    public function getIsPrivateAttribute(): bool
    {
        return !is_null($this->delivery_company);
    }

    public function getFullnameAttribute(): string
    {
        return $this->firstname ? $this->firstname  . ' ' . $this->lastname : $this->lastname;
    }

    public function delivery_company(): BelongsTo
    {
        return $this->belongsTo(DeliveryCompany::class, 'delivery_company_id');
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_driver_id');
    }
}
