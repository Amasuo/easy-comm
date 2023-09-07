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

    protected $fillable = [
        'delivery_company_id',
        'firstname',
        'lastname',
        'phone',
    ];

    protected $appends = [
        'is_private',
    ];

    public function getIsPrivateAttribute(): bool
    {
        return !is_null($this->delivery_company);
    }

    public function delivery_company(): BelongsTo
    {
        return $this->belongsTo(DeliveryCompany::class, 'delivery_company_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'delivery_driver_id');
    }
}
