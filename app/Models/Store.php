<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Store extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $table = 'stores';

    const SEARCHABLE = [
        'name',
        'patent_number',
    ];

    protected $fillable = [
        'name',
        'patent_number',
    ];

    protected $appends = [
        'image',
    ];

    protected $with = [
        'children'
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

    public function parent()
    {
        return $this->belongsTo(Store::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Store::class, 'parent_id');
    }

    public function users() {
        return $this->belongsToMany(User::class, 'role_store_user')
            ->withTimestamps();
    }

    public function addUser(User $user, $isAdmin = false)
    {
        $user->addStore($this, $isAdmin);
    }

    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class, 'store_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'store_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'store_id');
    }

    public function delivery_drivers(): HasMany
    {
        return $this->hasMany(DeliveryDriver::class, 'store_id');
    }
}
