<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;
    protected $table = 'stores';

    protected $fillable = [
        'name',
    ];

    protected $with = [
        'customers',
        'products',
    ];

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'store_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'store_id');
    }
}
