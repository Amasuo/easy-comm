<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryCompany extends Model
{
    use HasFactory;
    protected $table = 'delivery_companies';

    protected $fillable = [
        'name',
        'phone',
    ];

    protected $with = [
        'delviery_drivers',
    ];

    public function delviery_drivers(): HasMany
    {
        return $this->hasMany(DeliveryDriver::class, 'delivery_company_id');
    }
}
