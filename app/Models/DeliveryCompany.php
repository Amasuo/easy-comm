<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class DeliveryCompany extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    const SEARCHABLE = [
        'name',
    ];

    protected $table = 'delivery_companies';

    protected $fillable = [
        'name',
        'phone',
    ];

    protected $appends = [
        'image',
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('main')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('webp')
            ->format('webp')
            ->nonQueued()
            ->performOnCollections('main');
    }

    public function getImageAttribute()
    {
        return $this->getFirstMedia('main') ? $this->getFirstMedia('main')->original_url : env('NO_IMAGE_URL');
    }

    public function delviery_drivers(): HasMany
    {
        return $this->hasMany(DeliveryDriver::class, 'delivery_company_id');
    }
}
