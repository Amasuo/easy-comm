<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryCompany extends Model
{
    use HasFactory;

    const SEARCHABLE = [
        'name',
    ];

    protected $table = 'delivery_companies';

    protected $fillable = [
        'name',
        'phone',
    ];

    public function delviery_drivers(): HasMany
    {
        return $this->hasMany(DeliveryDriver::class, 'delivery_company_id');
    }
}
