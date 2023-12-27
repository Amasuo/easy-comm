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
        'store'
    ];
    
    protected $fillable = [
        'store_id',
        'name',
    ];

    protected $appends = [
        'price',
        'image',
    ];

    protected $hidden = [
        'price_int',
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
}
