<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $with = [
        'store'
    ];

    const SEARCHABLE = [
        'firstname',
        'lastname',
        'phone',
        'state',
        'city',
        'street',
    ];

    protected $fillable = [
        'store_id',
        'is_forbidden',
        'firstname',
        'lastname',
        'phone',
        'state',
        'city',
        'street',
    ];

    protected $appends = [
        'fullname',
    ];

    protected $casts = [
        'is_forbidden' => 'bool',
    ];

    public function getFullnameAttribute(): string
    {
        return $this->firstname ? $this->firstname  . ' ' . $this->lastname : $this->lastname;
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'customer_id');
    }
}
