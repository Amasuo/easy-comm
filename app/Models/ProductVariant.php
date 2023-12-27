<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class ProductVariant extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $table = 'product_variants';

    protected $with = [
        'product',
    ];
    protected $fillable = [
        'product_id',
        'stock',
    ];

    protected $appends = [
        'price',
        'image',
    ];

    protected $hidden = [
        'custom_price_int',
        'media',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')
            ->singleFile();

        /*$this->addMediaCollection('preview')
            ->singleFile();*/
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->nonQueued()
            ->performOnCollections('main');

        /*$this->addMediaConversion('webp')
            ->crop(Manipulations::CROP_CENTER, 300, 300)
            ->format('webp')
            ->nonQueued()
            ->performOnCollections('preview');*/
    }

    public function getImageAttribute()
    {
        return $this->getFirstMedia('main') ? $this->getFirstMedia('main')->original_url : $this->product->image;
    }

    public function getPriceAttribute()
    {
        return $this->custom_price ?? $this->product->price;
    }

    public function setPriceAttribute($value)
    {
        return $this->setCustomPriceAttribute($value);
    }

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

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function product_option_values(): BelongsToMany
    {
        return $this->belongsToMany(ProductOptionValue::class, 'product_option_value_product_variant');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_product_variant')
        ->withPivot([
            'count'
        ]);
    }
}
