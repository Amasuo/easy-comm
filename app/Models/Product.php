<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $table = 'products';

    const SEARCHABLE = [
        'name',
    ];

    protected $with = [
        'store',
        'product_options',
        'product_gender',
    ];
    
    protected $fillable = [
        'store_id',
        'name',
        'product_gender_id',
        'price_int',
        'purchase_price_int',
        'is_active'
    ];

    protected $appends = [
        'price',
        'purchase_price',
        'image',
    ];

    protected $hidden = [
        'price_int',
        'purchase_price_int',
        'media',
    ];

    protected $casts = [
        'is_active' => 'bool',
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
        return $this->getFirstMedia('main') ? $this->getFirstMedia('main')->original_url : null;
    }

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

    public function getPurchasePriceAttribute()
    {
        return $this->purchase_price_int ? $this->purchase_price_int / 10 : null;
    }

    public function setPurchasePriceAttribute($value)
    {
        $intValue = intval(round($value * 10), 0);
        $this->attributes['purchase_price_int'] = $intValue;
    }

    public function setIsActiveAttribute($value)
    {
        $this->attributes['is_active'] = $value;

        ProductVariant::whereIn('id', $this->product_variants()->pluck('id'))->update([
            'is_active' => $value,
        ]);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function product_variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }
    
    public function product_options(): HasMany
    {
        return $this->hasMany(ProductOption::class, 'product_id');
    }

    public function product_gender(): BelongsTo
    {
        return $this->belongsTo(ProductGender::class, 'product_gender_id');
    }
}
