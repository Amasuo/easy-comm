<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductOption extends Model
{
    use HasFactory;
    protected $table = 'product_options';

    protected $fillable = [
        'product_id',
        'name',
    ];

    protected $with = [
        'product_option_values',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_option_values(): HasMany
    {
        return $this->hasMany(ProductOptionValue::class, 'product_option_id');
    }
}
