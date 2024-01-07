<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductGender extends Model
{
    use HasFactory;
    protected $table = 'product_genders';

    const SEARCHABLE = [
        'name',
    ];

    protected $fillable = [
        'name',
    ];

    protected static function booted()
    {
        static::deleting(function ($productGender) {
            Product::where('product_gender_id', $productGender->id)->update(['product_gender_id' => null]);
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'product_gender_id');
    }
}
