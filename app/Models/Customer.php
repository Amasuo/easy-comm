<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'customers';

    protected $fillable = [
        'store_id',
        'firstname',
        'lastname',
        'phone',
        'state',
        'city',
        'street',
    ];
}
