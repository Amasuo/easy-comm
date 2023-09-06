<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductOptionValue extends Model
{
    use HasFactory;
    protected $table = 'product_option_values';

    protected $fillable = [
        'product_option_id',
        'value',
    ];

    public function product_option(): BelongsTo
    {
        return $this->belongsTo(ProductOption::class, 'product_option_id');
    }

    public function product_variants(): BelongsToMany
    {
        return $this->belongsToMany(ProductVariant::class, 'product_option_value_product_variant');
    }
}
