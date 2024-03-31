<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Store extends Model
{
    use HasFactory;
    protected $table = 'stores';

    const SEARCHABLE = [
        'name',
        'patent_number',
    ];

    protected $fillable = [
        'name',
        'patent_number',
    ];

    protected $with = [
        'children'
    ];

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
