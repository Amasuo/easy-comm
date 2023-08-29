<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;
    protected $table = 'product_variants';

    protected $fillable = [
        'product_id',
        'stock',
    ];

    protected $appends = [
        'custom_price'
    ];

    protected $hidden = [
        'custom_price_int'
    ];

    // example : stored as 1235 -> return 123.5 (dt)
    public function getCustomPriceAttribute()
    {
        return $this->custom_price_int ? $this->custom_price_int / 10 : null;
    }

    // example : passed 123.5 (dt) -> store as 1235
    public function setCustomPriceAttribute($value)
    {
        $intValue = intval(round($value * 10), 0);
        $this->attributes['custom_price_int'] = $intValue;
    }
}
