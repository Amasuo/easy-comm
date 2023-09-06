<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $fillable = [
        'store_id',
        'name',
    ];

    protected $appends = [
        'price'
    ];

    protected $hidden = [
        'price_int'
    ];

    // example : stored as 1235 -> return 123.5 (dt)
    public function getPriceAttribute()
    {
        return $this->price_int ? $this->price_int / 10 : null;
    }

    // example : passed 123.5 (dt) -> store as 1235
    public function setPriceAttribute($value)
    {
        $intValue = intval(round($value * 10), 0);
        $this->attributes['price_int'] = $intValue;
    }
    
    public function product_options(): HasMany
    {
        return $this->hasMany(Product::class, 'product_id');
    }
}
